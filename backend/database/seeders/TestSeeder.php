<?php
namespace Database\Seeders;

use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Test::factory()
            ->count(3)
            ->has(TestQuestion::factory()->count(32), 'testQuestions')
            ->create();
    }
}
