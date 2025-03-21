<?php
namespace Database\Factories;

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
            'content' => $this->faker->sentence(),
            'media'   => $this->faker->word() . '.jpg',
            'type'    => $this->faker->randomElement(['basic', 'specialist']),
            'points'  => $this->faker->numberBetween(1, 3),
        ];
    }
}
