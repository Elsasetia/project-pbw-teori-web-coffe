<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    // "feedback" tidak punya bentuk jamak di Laravel, jadi nama tabel ditulis eksplisit.
    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'name',
        'rating',
        'message',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('is_approved', true);
    }
}
