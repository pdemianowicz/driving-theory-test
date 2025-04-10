<?php

use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
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
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Test::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Question::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Answer::class)->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_correct')->nullable();
            $table->unsignedInteger('answer_time_taken')->nullable();
            $table->unsignedInteger('question_order');
            $table->unsignedTinyInteger('points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};
