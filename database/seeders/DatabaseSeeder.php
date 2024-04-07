<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\BillingArea;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();


        $areas = [
            ['name' => 'Barangay Kapatagan'],
            ['name' => 'Barangay Biu-os'],
            ['name' => 'Barangay Danan']
        ];

        foreach ($areas as $area) {
            BillingArea::create($area);
        }
    }
}
