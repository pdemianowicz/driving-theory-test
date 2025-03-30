<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'type',
        'media',
        'points',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_question');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(QuestionTranslation::class);
    }

    public function translation(?string $locale = null)
    {
        return $this->translations()->firstWhere('locale', $locale ?? app()->getLocale());
    }

    public function getContentAttribute(): ?string
    {
        return $this->translation()?->content;
    }

    public function getExplanationAttribute(): ?string
    {
        return $this->translation()?->explanation;
    }

}
