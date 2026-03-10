<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_agency_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_agency_id')->constrained('shipping_agencies')->cascadeOnDelete();
            $table->string('address');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['shipping_agency_id', 'address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_agency_addresses');
    }
};
