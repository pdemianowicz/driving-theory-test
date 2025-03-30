<?php
namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionTranslation;
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
            // 'content' => $this->faker->sentence(),
            'media'  => $this->faker->word() . '.jpg',
            'type'   => $this->faker->randomElement(['basic', 'specialist']),
            'points' => $this->faker->numberBetween(1, 3),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Question $question) {
            $locales = ['pl', 'en', 'de', 'uk'];
            foreach ($locales as $locale) {
                QuestionTranslation::factory()->create([
                    'question_id' => $question->id,
                    'locale'      => $locale,
                ]);
            }
        });
    }
}
