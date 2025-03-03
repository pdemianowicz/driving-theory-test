<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'number', 'content', 'media', 'question_type'];

    // public function category(): BelongsTo
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_question');
    }
}
