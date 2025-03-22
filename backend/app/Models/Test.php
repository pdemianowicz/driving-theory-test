<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    /** @use HasFactory<\Database\Factories\TestFactory> */
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = [
        'category_id',
        'user_id',
        'started_at',
        'completed_at',
        'score',
        'total_questions',
        'time_taken',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function testQuestions(): HasMany
    {
        return $this->hasMany(TestQuestion::class);
    }
}
