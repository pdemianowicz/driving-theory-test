<?php
namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $categoryIds = Category::pluck('id')->toArray();

        return [
            'category_id'     => $this->faker->randomElement($categoryIds),
            'user_id'         => null,
            'started_at'      => now()->subMinutes(rand(1, 60)),
            'completed_at'    => now(),
            'score'           => $this->faker->numberBetween(0, 100),
            'total_questions' => 32,
            'time_taken'      => $this->faker->numberBetween(60, 1500),
        ];

    }
}
