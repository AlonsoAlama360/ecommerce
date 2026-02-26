<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['id', 'name'];

    protected function casts(): array
    {
        return ['id' => 'integer'];
    }

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }
}
