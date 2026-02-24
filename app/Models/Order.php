<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'source',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'total',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'customer_notes',
        'admin_notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public const STATUS_LABELS = [
        'pendiente' => 'Pendiente',
        'confirmado' => 'Confirmado',
        'en_preparacion' => 'En preparación',
        'enviado' => 'Enviado',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado',
    ];

    public const STATUS_COLORS = [
        'pendiente' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'confirmado' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'en_preparacion' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
        'enviado' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600'],
        'entregado' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        'cancelado' => ['bg' => 'bg-red-50', 'text' => 'text-red-500'],
    ];

    public const PAYMENT_METHODS = [
        'efectivo' => 'Efectivo',
        'transferencia' => 'Transferencia',
        'yape_plin' => 'Yape / Plin',
        'tarjeta' => 'Tarjeta',
    ];

    public const PAYMENT_STATUS_LABELS = [
        'pendiente' => 'Pendiente',
        'pagado' => 'Pagado',
        'fallido' => 'Fallido',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::withTrashed()
            ->where('order_number', 'like', "ORD-{$date}-%")
            ->orderByDesc('order_number')
            ->first();

        $seq = 1;
        if ($lastOrder) {
            $seq = (int) substr($lastOrder->order_number, -4) + 1;
        }

        return "ORD-{$date}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): array
    {
        return self::STATUS_COLORS[$this->status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-500'];
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }
}
