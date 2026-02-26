<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['id', 'province_id', 'name'];

    protected function casts(): array
    {
        return ['id' => 'integer', 'province_id' => 'integer'];
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
