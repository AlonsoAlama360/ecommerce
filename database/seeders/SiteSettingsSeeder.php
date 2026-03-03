<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Negocio
            ['key' => 'business_name', 'value' => 'Arixna', 'group' => 'negocio', 'type' => 'text', 'label' => 'Nombre del negocio'],
            ['key' => 'legal_name', 'value' => 'Arixna E.I.R.L.', 'group' => 'negocio', 'type' => 'text', 'label' => 'Razón Social'],
            ['key' => 'ruc', 'value' => '', 'group' => 'negocio', 'type' => 'text', 'label' => 'RUC'],
            ['key' => 'address', 'value' => '', 'group' => 'negocio', 'type' => 'text', 'label' => 'Dirección'],
            ['key' => 'phone', 'value' => '', 'group' => 'negocio', 'type' => 'tel', 'label' => 'Teléfono'],
            ['key' => 'contact_email', 'value' => 'contacto@arixna.com', 'group' => 'negocio', 'type' => 'email', 'label' => 'Email de contacto'],
            ['key' => 'business_hours', 'value' => 'Lunes a Viernes, 9:00 am - 6:00 pm', 'group' => 'negocio', 'type' => 'text', 'label' => 'Horario de atención'],

            // Redes Sociales
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/arixna', 'group' => 'redes', 'type' => 'url', 'label' => 'Instagram'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/arixna', 'group' => 'redes', 'type' => 'url', 'label' => 'Facebook'],
            ['key' => 'tiktok_url', 'value' => 'https://tiktok.com/@arixna', 'group' => 'redes', 'type' => 'url', 'label' => 'TikTok'],
            ['key' => 'pinterest_url', 'value' => '', 'group' => 'redes', 'type' => 'url', 'label' => 'Pinterest'],
            ['key' => 'whatsapp_number', 'value' => '', 'group' => 'redes', 'type' => 'tel', 'label' => 'WhatsApp (con código país)'],
            ['key' => 'whatsapp_message', 'value' => 'Hola, me interesa obtener más información', 'group' => 'redes', 'type' => 'text', 'label' => 'Mensaje predeterminado WhatsApp'],

            // SEO
            ['key' => 'meta_description', 'value' => 'Arixna - Tu tienda online de perfumes, electrodomésticos, joyería y zapatillas. Envíos a todo el Perú.', 'group' => 'seo', 'type' => 'text', 'label' => 'Meta descripción'],
            ['key' => 'tagline', 'value' => 'Creando momentos inolvidables con detalles que expresan amor verdadero.', 'group' => 'seo', 'type' => 'text', 'label' => 'Eslogan / Tagline'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
