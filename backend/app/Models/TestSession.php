<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestSession extends Model
{
    /** @use HasFactory<\Database\Factories\TestSessionFactory> */
    use HasFactory;

    protected $fillable = ['uuid', 'category_id', 'is_completed', 'completed_at', 'user_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function testAnswers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
