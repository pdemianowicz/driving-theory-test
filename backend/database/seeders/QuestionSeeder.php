<?php
namespace Database\Seeders;

use App\Models\Answer;
use App\Models\AnswerTranslation;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $locales    = ['pl', 'en', 'de', 'uk'];

        $yesTranslations = ['pl' => 'Tak', 'en' => 'Yes', 'de' => 'Ja', 'uk' => 'Так'];
        $noTranslations  = ['pl' => 'Nie', 'en' => 'No', 'de' => 'Nein', 'uk' => 'Ні'];

        Question::factory(100)->create()->each(function ($question) use ($categories, $locales, $yesTranslations, $noTranslations) {
            $question->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            if ($question->type === 'basic') {

                $isCorrect = fake()->boolean();
                $yesAnswer = Answer::factory()->create(['question_id' => $question->id, 'is_correct' => $isCorrect]);
                foreach ($locales as $locale) {
                    AnswerTranslation::updateOrCreate(
                        ['answer_id' => $yesAnswer->id, 'locale' => $locale],
                        ['content' => $yesTranslations[$locale]]
                    );
                }

                $noAnswer = Answer::factory()->create(['question_id' => $question->id, 'is_correct' => ! $isCorrect]);
                foreach ($locales as $locale) {
                    AnswerTranslation::updateOrCreate(
                        ['answer_id' => $noAnswer->id, 'locale' => $locale],
                        ['content' => $noTranslations[$locale]]
                    );
                }

            } else {
                $answers = Answer::factory(3)->create([
                    'question_id' => $question->id,
                    'is_correct'  => false,
                ]);

                $correctAnswer             = $answers->random();
                $correctAnswer->is_correct = true;
                $correctAnswer->save();
            }
        });
    }
}
