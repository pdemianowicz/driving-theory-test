<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Answer extends Model
{
    /** @use HasFactory<\Database\Factories\AnswerFactory> */
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = [
        'question_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function questions(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(AnswerTranslation::class);
    }

    public function translation(?string $locale = null)
    {
        return $this->translations()->firstWhere('locale', $locale ?? app()->getLocale());
    }

    public function getContentAttribute(): ?string
    {
        return $this->translation()?->content;
    }
}
