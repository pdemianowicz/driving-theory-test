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
            $categories = explode(',', $item['Kategorie']);
            foreach ($categories as $categoryName) {
                $category = Category::firstOrCreate(['name' => trim($categoryName)]);

                // Utwórz pytanie
                $question = Question::create([
                    'category_id'   => $category->id,
                    'number'        => $item['Numer pytania'],
                    'content'       => $item['Pytanie'],
                    'media'         => $item['Media'],
                    'question_type' => 'basic', // Zakładam, że wszystkie pytania są typu 'basic'
                ]);

                // Utwórz odpowiedzi dla pytania
                $answers = [
                    ['content' => $item['Odpowiedź A'], 'is_correct' => $item['Poprawna odp'] === 'A'],
                    ['content' => $item['Odpowiedź B'], 'is_correct' => $item['Poprawna odp'] === 'B'],
                    ['content' => $item['Odpowiedź C'], 'is_correct' => $item['Poprawna odp'] === 'C'],
                ];

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
}
