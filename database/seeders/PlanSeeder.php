<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create(['name' => 'Anual', 'months' => 12]);
        Plan::create(['name' => 'Semestral', 'months' => 6]);
        Plan::create(['name' => 'Bianual', 'months' => 24]);
        Plan::create(['name' => 'Personalizado', 'months' => 0]);
    }
}