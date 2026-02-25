<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'stock_before' => 'integer',
            'stock_after' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public const TYPE_LABELS = [
        'entrada' => 'Entrada',
        'salida' => 'Salida',
        'ajuste' => 'Ajuste',
    ];

    public const TYPE_COLORS = [
        'entrada' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        'salida' => ['bg' => 'bg-red-50', 'text' => 'text-red-500'],
        'ajuste' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute(): array
    {
        return self::TYPE_COLORS[$this->type] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-500'];
    }

    public function getReferenceLabelAttribute(): ?string
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        $ref = $this->reference;
        if (!$ref) return null;

        if ($ref instanceof Purchase) {
            return $ref->purchase_number;
        }
        if ($ref instanceof Order) {
            return $ref->order_number;
        }

        return "#{$this->reference_id}";
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeByProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDateRange($query, ?string $from, ?string $to)
    {
        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to . ' 23:59:59');
        return $query;
    }
}
