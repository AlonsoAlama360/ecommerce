<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('payment_status');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('efectivo','transferencia','yape_plin','tarjeta','culqi') DEFAULT 'efectivo'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('efectivo','transferencia','yape_plin','tarjeta') DEFAULT 'efectivo'");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
        });
    }
};
