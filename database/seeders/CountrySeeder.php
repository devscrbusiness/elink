<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            ['name' => 'Ecuador', 'phone_code' => '593', 'iso_code' => 'EC'],
            ['name' => 'Perú', 'phone_code' => '51', 'iso_code' => 'PE'],
            ['name' => 'Colombia', 'phone_code' => '57', 'iso_code' => 'CO'],
            ['name' => 'Argentina', 'phone_code' => '54', 'iso_code' => 'AR'],
            ['name' => 'Chile', 'phone_code' => '56', 'iso_code' => 'CL'],
            ['name' => 'Venezuela', 'phone_code' => '58', 'iso_code' => 'VE'],
            ['name' => 'Bolivia', 'phone_code' => '591', 'iso_code' => 'BO'],
            ['name' => 'Uruguay', 'phone_code' => '598', 'iso_code' => 'UY'],
            ['name' => 'Paraguay', 'phone_code' => '595', 'iso_code' => 'PY'],
            ['name' => 'México', 'phone_code' => '52', 'iso_code' => 'MX'],
            ['name' => 'España', 'phone_code' => '34', 'iso_code' => 'ES'],
            ['name' => 'Brasil', 'phone_code' => '55', 'iso_code' => 'BR'],
            ['name' => 'USA/Canadá', 'phone_code' => '1', 'iso_code' => 'US'],
        ]);
    }
}