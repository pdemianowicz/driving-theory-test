<?php
namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryTranslation>
 */
class CategoryTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'locale'      => 'pl',
            // 'name'        => 'Category ' . strtoupper(fake()->randomLetter()),
            'description' => fake()->realText(120),

            'name'        => function (array $attributes) {
                // Pobierz przekazany kod bazowy i aktualny język
                $baseCode = $attributes['name'];
                $locale   = $attributes['locale'];

                // Zwróć nazwę w zależności od języka
                return match ($locale) {
                    'pl_PL'       => 'Kategoria ' . $baseCode,
                    'en_US'       => 'Category ' . $baseCode,
                    'de_DE'       => 'Kategorie ' . $baseCode,
                    'uk_UA'       => 'Категорія ' . $baseCode,
                    default       => 'Category ' . $baseCode, // Bezpieczny fallback
                };
            },
        ];
    }
}
