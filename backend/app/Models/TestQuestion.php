<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\TestQuestionFactory> */
    use HasFactory;

    protected $table = 'test_questions';

    protected $fillable = [
        'test_id',
        'question_id',
        'answer_id',
        'is_correct',
        'answer_time_taken',
        'question_order',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
