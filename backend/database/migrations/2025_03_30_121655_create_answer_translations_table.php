<?php

use App\Models\Answer;
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
        Schema::create('answer_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Answer::class)->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->text('content');
            $table->unique(['answer_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_translations');
    }
};
