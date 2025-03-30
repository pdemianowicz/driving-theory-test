<?php
namespace Database\Factories;

use App\Models\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnswerTranslation>
 */
class AnswerTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'answer_id' => Answer::factory(),
            'locale'    => 'pl',
            'content'   => $this->faker->sentence(5),
        ];
    }
}
