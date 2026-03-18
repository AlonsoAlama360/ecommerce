<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('efectivo','transferencia','yape_plin','tarjeta','culqi','asesor') DEFAULT 'efectivo'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('efectivo','transferencia','yape_plin','tarjeta','culqi') DEFAULT 'efectivo'");
    }
};
