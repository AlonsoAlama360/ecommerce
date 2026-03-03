<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->unique();

            // Datos del consumidor
            $table->string('consumer_name');
            $table->string('consumer_document_type'); // DNI, CE, Pasaporte
            $table->string('consumer_document_number');
            $table->string('consumer_email');
            $table->string('consumer_phone');
            $table->string('consumer_address')->nullable();

            // Datos del apoderado (opcional)
            $table->string('representative_name')->nullable();
            $table->string('representative_email')->nullable();

            // Tipo de bien contratado
            $table->enum('product_type', ['producto', 'servicio']);
            $table->string('product_description');
            $table->string('order_number')->nullable();

            // Tipo de reclamo
            $table->enum('complaint_type', ['reclamo', 'queja']);
            $table->text('complaint_detail');
            $table->text('consumer_request');

            // Respuesta del proveedor
            $table->text('provider_response')->nullable();
            $table->date('response_date')->nullable();

            $table->enum('status', ['pendiente', 'en_proceso', 'resuelto'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
