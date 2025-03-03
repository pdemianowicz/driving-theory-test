<?php
namespace Database\Factories;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number'        => fake()->unique()->numberBetween(1, 9000),
            'content'       => fake()->sentence(6),
            'media'         => fake()->word(),
            'question_type' => fake()->randomElement(['basic', 'specialist']),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Question $question) {

            $categories = Category::inRandomOrder()->limit(3)->pluck('id');
            $question->categories()->attach($categories);

            if ($question->question_type === 'basic') {
                $answers = ['Tak', 'Nie'];
            } else {
                $answers = [fake()->sentence(3), fake()->sentence(3), fake()->sentence(3)];
            }

            $correctAnswer = fake()->randomElement($answers);

            foreach ($answers as $answer) {
                Answer::factory()->create([
                    'question_id'    => $question->id,
                    'answer_content' => $answer,
                    'is_correct'     => $answer === $correctAnswer,
                ]);
            }
        });
    }
}
