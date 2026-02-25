<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'status',
        'subtotal',
        'tax_amount',
        'total',
        'expected_date',
        'received_date',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'expected_date' => 'date',
            'received_date' => 'date',
        ];
    }

    public const STATUS_LABELS = [
        'pendiente' => 'Pendiente',
        'aprobado' => 'Aprobado',
        'en_transito' => 'En tránsito',
        'recibido' => 'Recibido',
        'cancelado' => 'Cancelado',
    ];

    public const STATUS_COLORS = [
        'pendiente' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'aprobado' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'en_transito' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600'],
        'recibido' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        'cancelado' => ['bg' => 'bg-red-50', 'text' => 'text-red-500'],
    ];

    protected static function booted(): void
    {
        static::creating(function (Purchase $purchase) {
            if (empty($purchase->purchase_number)) {
                $purchase->purchase_number = static::generatePurchaseNumber();
            }
        });
    }

    public static function generatePurchaseNumber(): string
    {
        $date = now()->format('Ymd');
        $last = static::withTrashed()
            ->where('purchase_number', 'like', "COM-{$date}-%")
            ->orderByDesc('purchase_number')
            ->first();

        $seq = 1;
        if ($last) {
            $seq = (int) substr($last->purchase_number, -4) + 1;
        }

        return "COM-{$date}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): array
    {
        return self::STATUS_COLORS[$this->status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-500'];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySupplier($query, int $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }
}
