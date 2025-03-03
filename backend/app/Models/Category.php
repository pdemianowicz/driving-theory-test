<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // public function questions(): HasMany
    // {
    //     return $this->hasMany(Question::class);
    // }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'category_question');
    }
}
