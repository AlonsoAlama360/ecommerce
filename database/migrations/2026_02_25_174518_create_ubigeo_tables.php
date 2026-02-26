<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->string('name', 100);
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->unsignedTinyInteger('department_id');
            $table->string('name', 100);

            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete();
            $table->index('department_id');
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->primary();
            $table->unsignedSmallInteger('province_id');
            $table->string('name', 100);

            $table->foreign('province_id')->references('id')->on('provinces')->cascadeOnDelete();
            $table->index('province_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('departments');
    }
};
