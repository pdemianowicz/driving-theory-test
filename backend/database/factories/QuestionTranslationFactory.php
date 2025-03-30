<?php
namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionTranslation>
 */
class QuestionTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'locale'      => 'pl',
            'content'     => $this->faker->sentence() . '?',
            'explanation' => $this->faker->paragraph(rand(2, 4)),
        ];
    }
}
