<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_notification_types', function (Blueprint $table) {
            $table->string('role', 50);
            $table->string('notification_type', 50);
            $table->primary(['role', 'notification_type']);
        });

        // Por defecto: el rol admin recibe todas las notificaciones
        $types = ['new_order', 'low_stock', 'new_contact', 'new_complaint', 'new_review'];
        foreach ($types as $type) {
            DB::table('role_notification_types')->insert([
                'role' => 'admin',
                'notification_type' => $type,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_notification_types');
    }
};
