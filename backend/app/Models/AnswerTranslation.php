<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\AnswerTranslationFactory> */
    use HasFactory;

    protected $table = 'answer_translations';

    protected $fillable = [
        'answer_id',
        'locale',
        'content',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
