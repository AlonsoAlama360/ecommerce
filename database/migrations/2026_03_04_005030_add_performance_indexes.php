<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // users: queries filter by role, is_active, created_at frequently
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('is_active');
            $table->index('created_at');
        });

        // suppliers: filtered by is_active in purchase forms and reports
        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('is_active');
        });

        // contact_messages: dashboard counts by status
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('status');
        });

        // complaints: dashboard counts pending (response_date IS NULL)
        Schema::table('complaints', function (Blueprint $table) {
            $table->index('response_date');
        });

        // wishlists: sorted by created_at in admin wishlist list
        Schema::table('wishlists', function (Blueprint $table) {
            $table->index('created_at');
        });

        // orders: composite for dashboard queries (status + created_at together)
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
        });

        // products: stock queries for low/out of stock alerts
        Schema::table('products', function (Blueprint $table) {
            $table->index('stock');
        });

        // stock_movements: composite for kardex date range queries per product
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index(['product_id', 'created_at']);
        });

        // purchases: composite for dashboard/report queries
        Schema::table('purchases', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropIndex(['response_date']);
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['stock']);
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'created_at']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
        });
    }
};
