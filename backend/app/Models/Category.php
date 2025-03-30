<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [

    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'category_question');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation(?string $locale = null)
    {
        return $this->translations()->firstWhere('locale', $locale ?? app()->getLocale());
    }

    public function getNameAttribute(): ?string
    {
        return $this->translation()?->name;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->translation()?->description;
    }

}
