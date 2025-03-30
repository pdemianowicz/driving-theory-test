<?php
namespace Database\Factories;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'name'        => 'Kategoria ' . strtoupper(fake()->unique()->randomLetter),
            // 'description' => fake()->realText(120),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Category $category) {
            $locales          = ['pl', 'en', 'de', 'uk'];
            $baseCategoryCode = strtoupper(fake()->unique()->randomLetter());
            foreach ($locales as $locale) {
                CategoryTranslation::factory()->create([
                    'category_id' => $category->id,
                    'locale'      => $locale,
                    'name'        => $baseCategoryCode,
                ]);
            }
        });
    }
}
