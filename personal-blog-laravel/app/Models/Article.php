<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'dop',
        'content',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'dop' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['title'] ?? null, function ($query, $title) {
                $query->where('title', 'like', "%{$title}%");
            })
            ->when($filters['dop'] ?? null, function ($query, $dop) {
                $query->whereDate('dop', $dop);
            });
    }
}
