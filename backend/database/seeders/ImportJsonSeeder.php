<?php
namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ImportJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/plik.json');

        $jsonData = File::get($jsonPath);
        $data     = json_decode($jsonData, true);

        // Iteruj przez dane i zaimportuj je do bazy danych
        foreach ($data as $item) {
            // Znajdź lub utwórz kategorię
            $categories  = explode(',', $item['Kategorie']);
            $categoryIds = [];
            foreach ($categories as $categoryName) {
                $category      = Category::firstOrCreate(['name' => trim($categoryName)]);
                $categoryIds[] = $category->id;
            }

            // Określ typ pytania na podstawie obecności odpowiedzi
            $isSpecialist = ! empty($item['Odpowiedź A']) || ! empty($item['Odpowiedź B']) || ! empty($item['Odpowiedź C']);
            $questionType = $isSpecialist ? 'specialist' : 'basic';

            // Utwórz pytanie
            $question = Question::create([
                'number'        => $item['Numer pytania'],
                'content'       => $item['Pytanie'],
                'media'         => $item['Media'],
                'question_type' => $questionType,
            ]);

            // Przypisz kategorie do pytania
            $question->categories()->attach($categoryIds);

            // Utwórz odpowiedzi dla pytania
            if ($questionType === 'basic') {
                $answers = [
                    ['content' => 'Tak', 'is_correct' => $item['Poprawna odp'] === 'Tak'],
                    ['content' => 'Nie', 'is_correct' => $item['Poprawna odp'] === 'Nie'],
                ];
            } else {
                $answers = [
                    ['content' => $item['Odpowiedź A'], 'is_correct' => $item['Poprawna odp'] === 'A'],
                    ['content' => $item['Odpowiedź B'], 'is_correct' => $item['Poprawna odp'] === 'B'],
                    ['content' => $item['Odpowiedź C'], 'is_correct' => $item['Poprawna odp'] === 'C'],
                ];
            }

            foreach ($answers as $answerData) {
                if (! empty($answerData['content'])) {
                    Answer::create([
                        'question_id'    => $question->id,
                        'answer_content' => $answerData['content'],
                        'is_correct'     => $answerData['is_correct'],
                    ]);
                }
            }
        }
    }

}
