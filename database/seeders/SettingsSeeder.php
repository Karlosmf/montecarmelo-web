<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'contact.email', 'value' => 'contacto@montecarmelo.com.ar', 'group' => 'contact'],
            ['key' => 'contact.phone', 'value' => '+54 9 3482 53-5220', 'group' => 'contact'],
            ['key' => 'contact.whatsapp', 'value' => '5493482535220', 'group' => 'contact'],
            ['key' => 'contact.whatsapp_display', 'value' => '+54 9 3482 53-5220', 'group' => 'contact'],
            ['key' => 'store.name', 'value' => 'Monte Carmelo', 'group' => 'store'],
            ['key' => 'store.address', 'value' => 'Pje. 44-46, S3560 Reconquista, Santa Fe', 'group' => 'store'],
            ['key' => 'store.hours', 'value' => 'Lunes a Viernes de 08:00 a 17:00', 'group' => 'store'],
            ['key' => 'store.city', 'value' => 'Reconquista', 'group' => 'store'],
            ['key' => 'store.province', 'value' => 'Santa Fe', 'group' => 'store'],
            ['key' => 'social.instagram', 'value' => 'https://www.instagram.com/montecarmeloarg/', 'group' => 'social'],
            ['key' => 'social.facebook', 'value' => 'https://www.facebook.com/montecarmeloarg', 'group' => 'social'],
            ['key' => 'brand.name', 'value' => 'Monte Carmelo', 'group' => 'brand'],
            ['key' => 'brand.tagline', 'value' => 'Charcuterie & Premium Goods', 'group' => 'brand'],
            ['key' => 'brand.slogan', 'value' => 'Artesanos del sabor', 'group' => 'brand'],
        ];

        foreach ($settings as $setting) {
            Setting::set($setting['key'], $setting['value'], $setting['group']);
        }
    }
}
