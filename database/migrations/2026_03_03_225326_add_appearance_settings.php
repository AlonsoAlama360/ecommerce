<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')->insert([
            [
                'key' => 'site_logo',
                'value' => null,
                'group' => 'apariencia',
                'type' => 'image',
                'label' => 'Logo del sitio',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'group' => 'apariencia',
                'type' => 'image',
                'label' => 'Favicon (icono de pestaña)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', ['site_logo', 'site_favicon'])->delete();
    }
};
