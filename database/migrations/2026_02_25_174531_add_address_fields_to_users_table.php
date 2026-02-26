<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('document_type', ['DNI', 'CE', 'RUC'])->nullable()->after('phone');
            $table->string('document_number', 20)->nullable()->after('document_type');
            $table->unsignedTinyInteger('department_id')->nullable()->after('document_number');
            $table->unsignedSmallInteger('province_id')->nullable()->after('department_id');
            $table->unsignedMediumInteger('district_id')->nullable()->after('province_id');
            $table->string('address')->nullable()->after('district_id');
            $table->string('address_reference')->nullable()->after('address');

            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('province_id')->references('id')->on('provinces')->nullOnDelete();
            $table->foreign('district_id')->references('id')->on('districts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['province_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['document_type', 'document_number', 'department_id', 'province_id', 'district_id', 'address', 'address_reference']);
        });
    }
};
