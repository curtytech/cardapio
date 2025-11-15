<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPageView extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'views_count',
        'last_viewed_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}