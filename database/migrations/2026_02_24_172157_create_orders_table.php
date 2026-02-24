<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('source', ['web', 'admin'])->default('web');
            $table->enum('status', ['pendiente', 'confirmado', 'en_preparacion', 'enviado', 'entregado', 'cancelado'])->default('pendiente');
            $table->enum('payment_method', ['efectivo', 'transferencia', 'yape_plin', 'tarjeta'])->default('efectivo');
            $table->enum('payment_status', ['pendiente', 'pagado', 'fallido'])->default('pendiente');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('customer_name');
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_email')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('source');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
