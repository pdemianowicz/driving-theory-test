<?php
namespace Database\Seeders;

use App\Models\Answer;
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

        Question::factory(100)->create()->each(function ($question) use ($categories) {
            $question->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            if ($question->type === 'basic') {

                $isCorrect = fake()->boolean();
                Answer::factory()->create(['question_id' => $question->id, 'content' => 'Yes', 'is_correct' => $isCorrect]);
                Answer::factory()->create(['question_id' => $question->id, 'content' => 'No', 'is_correct' => ! $isCorrect]);

            } else {
                $answers = [
                    Answer::factory()->create(['question_id' => $question->id]),
                    Answer::factory()->create(['question_id' => $question->id]),
                    Answer::factory()->create(['question_id' => $question->id]),
                ];

                $correctAnswerIndex                       = fake()->numberBetween(0, 2);
                $answers[$correctAnswerIndex]->is_correct = true;
                $answers[$correctAnswerIndex]->save();
            }
        });
    }
}
