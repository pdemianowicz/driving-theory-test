<?php
namespace Database\Seeders;

use App\Models\Answer;
use App\Models\AnswerTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Question;
use App\Models\QuestionTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ImportCsvSeeder extends Seeder
{

    public function run(): void
    {
        $csvPath = database_path('baza-pytan.csv');

        if (! file_exists($csvPath) || ! is_readable($csvPath)) {
            $this->command->error("Plik CSV nie istnieje lub nie można go odczytać: " . $csvPath);
            return;
        }

        $fileHandle = fopen($csvPath, 'r');
        if ($fileHandle === false) {
            $this->command->error("Nie można otworzyć pliku CSV: " . $csvPath);
            return;
        }

        $header = fgetcsv($fileHandle);
        if ($header === false || empty($header)) {
            $this->command->error("Nie można odczytać nagłówka lub plik CSV jest pusty: " . $csvPath);
            fclose($fileHandle);
            return;
        }

        DB::beginTransaction();
        $rowCount    = 0;
        $skippedRows = 0;

        try {
            while (($rowData = fgetcsv($fileHandle)) !== false) {
                $rowCount++;
                // Upewnij się, że wiersz nie jest pusty i ma poprawną liczbę kolumn
                if (count($rowData) !== count($header)) {
                    Log::warning("Pominięto wiersz {$rowCount}: Niezgodna liczba kolumn. Oczekiwano " . count($header) . ", otrzymano " . count($rowData) . ". Dane: " . implode(',', $rowData));
                    $this->command->warn("Pominięto wiersz {$rowCount}: Niezgodna liczba kolumn.");
                    $skippedRows++;
                    continue;
                }
                // Sprawdź czy wiersz zawiera same puste wartości (czasem się zdarza w CSV)
                if (empty(array_filter($rowData))) {
                    Log::info("Pominięto pusty wiersz {$rowCount}.");
                    $skippedRows++;
                    continue;
                }

                $row = array_combine($header, $rowData);

                // --- Kategorie (Uproszczone tworzenie z polskim slugiem) ---
                $categoryNames = explode(',', $row['Kategorie'] ?? '');
                $categoryIds   = [];
                foreach ($categoryNames as $categoryName) {
                    $trimmedName = trim($categoryName); // Np. "B", "AM"
                    if (empty($trimmedName)) {
                        continue;
                    }

                    $categoryId = null;

                    // Sprawdź cache najpierw
                    if (isset($categoryCache[$trimmedName])) {
                        $categoryId = $categoryCache[$trimmedName];
                    } else {
                        // Jeśli nie ma w cache, spróbuj znaleźć lub stworzyć w bazie
                        // Użyjemy transakcji dla bezpieczeństwa tworzenia Category i Translation
                        try {
                            DB::transaction(function () use ($trimmedName, &$categoryId, &$categoryCache, $rowCount) {
                                // 1. Znajdź lub stwórz główny rekord Category powiązany z polskim tłumaczeniem
                                //    Szukamy tłumaczenia, bo ono ma unikalną nazwę (trimmedName)
                                $translation = CategoryTranslation::where('locale', 'pl')
                                    ->where('name', $trimmedName)
                                    ->first();

                                if ($translation) {
                                                                        // Znaleziono istniejące tłumaczenie, pobierz ID kategorii
                                    $category = $translation->category; // Zakładając poprawną relację w modelu
                                    if (! $category) {
                                        // Rzadki przypadek osieroconego tłumaczenia - zaloguj i obsłuż
                                        throw new \Exception("Osierocone tłumaczenie dla '{$trimmedName}' (ID: {$translation->id})");
                                    }
                                    $categoryId = $category->id;
                                    $createdNew = false; // Flaga wskazująca, czy tworzyliśmy nowy rekord
                                } else {
                                    // Nie znaleziono tłumaczenia, stwórz nową kategorię
                                    $category   = Category::create();
                                    $categoryId = $category->id;
                                    $createdNew = true;
                                }

                                // 2. Stwórz lub zaktualizuj polskie tłumaczenie, dodając slug
                                //    Używamy updateOrCreate, aby obsłużyć oba przypadki (nowy/istniejący)
                                //    i dodać slug, jeśli go brakuje.
                                CategoryTranslation::updateOrCreate(
                                    [ // Warunki wyszukiwania
                                        'category_id' => $categoryId,
                                        'locale'      => 'pl',
                                    ],
                                    [ // Wartości do wstawienia/aktualizacji
                                        'name' => 'Kategoria ' . $trimmedName,
                                        'slug' => Str::slug('Kategoria ' . $trimmedName), // Generuj slug z krótkiej nazwy
                                                                                          // 'description' => null, // Możesz dodać, jeśli potrzebujesz
                                    ]
                                );

                                // Zaktualizuj cache
                                $categoryCache[$trimmedName] = $categoryId;

                                if ($createdNew) {
                                    $this->command->info("Row {$rowCount}: Utworzono kategorię: '{$trimmedName}' (ID: {$categoryId}) wraz ze slugiem PL.");
                                }

                            }); // Koniec transakcji dla jednej kategorii

                        } catch (Throwable $e) {
                            // Złap błąd z transakcji, zaloguj i zdecyduj co dalej
                            Log::error("Błąd podczas tworzenia/aktualizacji kategorii '{$trimmedName}' w wierszu {$rowCount}: " . $e->getMessage());
                            $this->command->error("Błąd przetwarzania kategorii '{$trimmedName}' w wierszu {$rowCount}. Pomijanie przypisania tej kategorii.");
                            // Możesz zdecydować, czy pominąć całe pytanie, czy tylko tę kategorię
                            // Na razie pomijamy tylko przypisanie tej kategorii - pętla continue nie jest tu potrzebna
                        }

                    } // Koniec else (nie było w cache)

                    // Dodaj ID do listy, jeśli zostało poprawnie uzyskane
                    if ($categoryId) {
                        $categoryIds[] = $categoryId;
                    }

                } // Koniec pętli foreach dla nazw kategorii

                // --- Pytanie ---
                $questionTypeRaw = strtoupper(trim($row['Typ'] ?? ''));
                $questionType    = match ($questionTypeRaw) {
                    'SPECJALISTYCZNY' => 'specialist',
                    'PODSTAWOWY' => 'basic',
                    default => null,
                };

                if ($questionType === null) {
                    Log::warning("Pominięto pytanie w wierszu {$rowCount}: Nieznany lub brakujący typ pytania '{$row['Typ']}'.");
                    $this->command->warn("Pominięto pytanie w wierszu {$rowCount}: Nieznany lub brakujący typ pytania '{$row['Typ']}'.");
                    $skippedRows++;
                    continue;
                }
                if (empty(trim($row['Pytanie'] ?? ''))) {
                    Log::warning("Pominięto pytanie w wierszu {$rowCount}: Brak treści pytania w języku polskim.");
                    $this->command->warn("Pominięto pytanie w wierszu {$rowCount}: Brak treści pytania w języku polskim.");
                    $skippedRows++;
                    continue;
                }

                // --- Przetwarzanie nazwy pliku Media ---
                $originalMediaFilename = trim($row['Media'] ?? '');
                $finalMediaFilename    = null;

                if (! empty($originalMediaFilename)) {
                    // Pobierz rozszerzenie pliku (w małych literach dla porównania)
                    $extension = strtolower(pathinfo($originalMediaFilename, PATHINFO_EXTENSION));
                    // Pobierz nazwę pliku bez rozszerzenia
                    $filenameWithoutExtension = pathinfo($originalMediaFilename, PATHINFO_FILENAME);

                    if (in_array($extension, ['jpg', 'jpeg'])) {
                        // Jeśli to JPG/JPEG, zmień rozszerzenie na webp
                        $finalMediaFilename = $filenameWithoutExtension . '.webp';
                        Log::debug("Row {$rowCount}: Zmieniono rozszerzenie media z '{$originalMediaFilename}' na '{$finalMediaFilename}'");
                    } elseif ($extension === 'wmv') {
                        // Jeśli to WMV, zmień rozszerzenie na mp4
                        $finalMediaFilename = $filenameWithoutExtension . '.mp4';
                        Log::debug("Row {$rowCount}: Zmieniono rozszerzenie media z '{$originalMediaFilename}' na '{$finalMediaFilename}'");
                    } else {
                        // Dla innych rozszerzeń (lub braku) zostaw oryginalną nazwę
                        $finalMediaFilename = $originalMediaFilename;
                        // Możesz dodać logowanie, jeśli chcesz wiedzieć o innych typach plików
                        if ($extension !== '') {
                            Log::debug("Row {$rowCount}: Zachowano oryginalne rozszerzenie media: '{$originalMediaFilename}'");
                        }
                    }
                }
                // Jeśli $originalMediaFilename był pusty, $finalMediaFilename pozostanie null

                // Utwórz główny rekord pytania z przetworzoną nazwą pliku media
                $question = Question::create([
                    'type'   => $questionType,
                    'media'  => $finalMediaFilename, // Użyj przetworzonej nazwy
                    'points' => (int) ($row['Punkty'] ?? 1),
                ]);

                // Utwórz tłumaczenia dla pytania
                $questionTranslationsData = [
                    'pl' => ['content' => trim($row['Pytanie'] ?? '') /* 'explanation' => ... */],
                    'en' => ['content' => trim($row['Pytanie [ENG]'] ?? '') /* 'explanation' => ... */],
                    'de' => ['content' => trim($row['Pytanie [DE]'] ?? '') /* 'explanation' => ... */],
                    'ua' => ['content' => trim($row['Pytanie [UA]'] ?? '') /* 'explanation' => ... */],
                ];

                foreach ($questionTranslationsData as $locale => $data) {
                    if (! empty($data['content'])) { // Twórz tylko jeśli jest treść
                        QuestionTranslation::create([
                            'question_id' => $question->id,
                            'locale'      => $locale,
                            'content'     => $data['content'],
                            // 'explanation' => $data['explanation'] ?? null,
                        ]);
                    }
                }

                // Przypisz kategorie do pytania
                if (! empty($categoryIds)) {
                    $question->categories()->sync($categoryIds);
                }

                // --- Odpowiedzi ---
                $correctAnswerKey = trim($row['Poprawna odp'] ?? '');

                if ($questionType === 'basic') {
                    $basicAnswers = [
                        'Tak' => ['en' => 'Yes', 'de' => 'Ja', 'ua' => 'Так'],
                        'Nie' => ['en' => 'No', 'de' => 'Nein', 'ua' => 'Ні'],
                    ];
                    foreach ($basicAnswers as $plAnswer => $translations) {
                        // Utwórz główny rekord odpowiedzi
                        $answer = Answer::create([
                            'question_id' => $question->id,
                            'is_correct'  => ($correctAnswerKey === ($plAnswer === 'Tak' ? 'T' : 'N')),
                        ]);
                        // Utwórz tłumaczenie PL
                        AnswerTranslation::create([
                            'answer_id' => $answer->id,
                            'locale'    => 'pl',
                            'content'   => $plAnswer,
                        ]);
                        // Utwórz pozostałe tłumaczenia
                        foreach ($translations as $locale => $content) {
                            AnswerTranslation::create([
                                'answer_id' => $answer->id,
                                'locale'    => $locale,
                                'content'   => $content,
                            ]);
                        }
                    }
                } else { // specialist
                    $specialistAnswers = [
                        'A' => ['pl' => $row['Odpowiedź A'] ?? '', 'en' => $row['Odpowiedź A [ENG]'] ?? '', 'de' => $row['Odpowiedź A [DE]'] ?? '', 'ua' => $row['Odpowiedź A [UA]'] ?? ''],
                        'B' => ['pl' => $row['Odpowiedź B'] ?? '', 'en' => $row['Odpowiedź B [ENG]'] ?? '', 'de' => $row['Odpowiedź B [DE]'] ?? '', 'ua' => $row['Odpowiedź B [UA]'] ?? ''],
                        'C' => ['pl' => $row['Odpowiedź C'] ?? '', 'en' => $row['Odpowiedź C [ENG]'] ?? '', 'de' => $row['Odpowiedź C [DE]'] ?? '', 'ua' => $row['Odpowiedź C [UA]'] ?? ''],
                    ];

                    foreach ($specialistAnswers as $key => $translations) {
                        $trimmedPlContent = trim($translations['pl']);
                        // Utwórz odpowiedź tylko jeśli treść PL nie jest pusta
                        if (! empty($trimmedPlContent)) {
                            // Utwórz główny rekord odpowiedzi
                            $answer = Answer::create([
                                'question_id' => $question->id,
                                'is_correct'  => ($correctAnswerKey === $key),
                            ]);

                            // Utwórz tłumaczenia
                            foreach ($translations as $locale => $content) {
                                $trimmedContent = trim($content);
                                if (! empty($trimmedContent)) {
                                    AnswerTranslation::create([
                                        'answer_id' => $answer->id,
                                        'locale'    => $locale,
                                        'content'   => $trimmedContent,
                                    ]);
                                }
                            }
                        }
                    }
                }
                // Usunięto logowanie per wiersz dla przyspieszenia, można dodać if ($rowCount % 100 == 0) ...
                if ($rowCount % 100 == 0) {
                    $this->command->line("Przetworzono {$rowCount} wierszy...");
                }

            } // end while

            DB::commit();
            $totalProcessed = $rowCount - $skippedRows;
            $this->command->info("Import zakończony pomyślnie.");
            $this->command->info("Odczytano wierszy: {$rowCount}");
            $this->command->info("Pominięto wierszy: {$skippedRows}");
            $this->command->info("Zaimportowano pytań (z odpowiedziami i kategoriami): {$totalProcessed}");

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Błąd podczas importu CSV w okolicy wiersza {$rowCount}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->command->error("Wystąpił błąd podczas importu w okolicy wiersza {$rowCount}. Sprawdź logi (`storage/logs/laravel.log`). Zmiany zostały wycofane.");
            $this->command->error("Błąd: " . $e->getMessage());
        } finally {
            if ($fileHandle) {
                fclose($fileHandle);
            }
        }
    }
}
