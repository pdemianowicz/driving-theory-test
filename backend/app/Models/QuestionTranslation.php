<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionTranslationFactory> */
    use HasFactory;

    protected $table = 'question_translations';

    protected $fillable = [
        'question_id',
        'locale',
        'content',
        'explanation',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

}
