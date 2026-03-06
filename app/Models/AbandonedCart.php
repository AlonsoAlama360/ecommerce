<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbandonedCart extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'cart_data',
        'email_sent_at',
        'recovered_at',
    ];

    protected function casts(): array
    {
        return [
            'cart_data' => 'array',
            'email_sent_at' => 'datetime',
            'recovered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
