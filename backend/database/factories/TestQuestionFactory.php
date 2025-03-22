<?php
namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestQuestion>
 */
class TestQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questionIds = Question::pluck('id')->toArray();
        $answerIds   = Answer::pluck('id')->toArray();

        return [
            'test_id'           => Test::factory(),
            'question_id'       => $this->faker->randomElement($questionIds),
            'answer_id'         => $this->faker->randomElement($answerIds),
            'is_correct'        => $this->faker->boolean(),
            'answer_time_taken' => $this->faker->numberBetween(10, 50),
            'question_order'    => $this->faker->numberBetween(1, 32),
        ];
    }
}
