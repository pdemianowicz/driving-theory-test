<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Question;
use App\Models\TestAnswer;
use App\Models\TestSession;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(12)->create();
        Question::factory(50)->create();
        TestSession::factory(5)->create();
        TestAnswer::factory(20)->create();

        //   $this->call([
        //     ImportJsonSeeder::class,
        // ]);
    }
}
