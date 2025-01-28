<?php
namespace Database\Factories;

use App\Models\Question;
use App\Models\TestSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestAnswer>
 */
class TestAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question = Question::inRandomOrder()->first();
        $answer   = fake()->boolean(70)
        ? $question->answers()->inRandomOrder()->first()
        : null;

        return [
            'test_session_id' => TestSession::inRandomOrder()->limit(1)->first()->id,
            'question_id'     => $question->id,
            'user_answer_id'  => $answer ? $answer->id : null,
        ];
    }
}
