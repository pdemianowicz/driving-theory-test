<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)->constrained()->onDelete('set null');
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('max_possible_score')->default(0);
            $table->unsignedInteger('total_questions');
            $table->unsignedInteger('time_taken')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
