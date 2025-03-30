<?php
namespace Database\Factories;

use App\Models\Answer;
use App\Models\AnswerTranslation;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
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
            // 'content'     => $this->faker->sentence(5),
            'is_correct'  => false,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Answer $answer) {
            $locales = ['pl', 'en', 'de', 'uk'];
            foreach ($locales as $locale) {

                AnswerTranslation::factory()->create([
                    'answer_id' => $answer->id,
                    'locale'    => $locale,
                ]);
            }
        });
    }

}
