<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\BillingArea;
use App\Models\Consumer;
use App\Models\User;
use Carbon\Carbon;
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

        Consumer::create([
            'meter_code' => '2024-0000001',
            'first_name' => "test",
            'last_name' => "test",
            'phone_no' => "12345",
            'street' => 'street test',
            'barangay' => 'Barangay Biu-os',
        ]);

        User::create([
            'assign_id' => 2,
            'username' => 'test_collector',
            'first_name' => 'collector',
            'last_name' => 'test',
            'password' => Hash::make("password"),
            'phone_no' => '09123456',
            'street' => 'street',
            'barangay' => 'Barangay Biu-os',
            'is_deleted' => 0,
            'remember_token' => Str::random(60),
            'status' => 1
        ]);

        User::create([
            'username' => 'cashier',
            'first_name' => 'cash',
            'last_name' => 'test',
            'password' => Hash::make("password"),
            'phone_no' => '09123456',
            'street' => 'street',
            'barangay' => 'Barangay Biu-os',
            'is_deleted' => 0,
            'remember_token' => Str::random(60),
            'status' => 2
        ]);

        $test_billings = [
            [
                'consumer_id' => 1,
                'collector_id' => 2,
                'reading_date' => Carbon::now()->subWeek(),
                'due_date' => Carbon::now(),
                'previos' => 0,
                'current' => 1,
                'status' => 'PENDING',
                'price' => 20.00,
                'total' => 20.00,
                'after_due' => 70.00,
                'total_consumption' => 1
            ],
            [
                'consumer_id' => 1,
                'collector_id' => 2,
                'reading_date' => Carbon::now()->subWeeks(2),
                'due_date' => Carbon::now(),
                'previos' => 1,
                'current' => 2,
                'status' => 'PENDING',
                'price' => 20.00,
                'total' => 20.00,
                'after_due' => 70.00,
                'total_consumption' => 1
            ],
            [
                'consumer_id' => 1,
                'collector_id' => 2,
                'reading_date' => Carbon::now()->subWeeks(3),
                'due_date' => Carbon::now(),
                'previos' => 2,
                'current' => 3,
                'status' => 'PENDING',
                'price' => 20.00,
                'total' => 20.00,
                'after_due' => 70.00,
                'total_consumption' => 1
            ]
        ];


        foreach ($test_billings as $test_billing) {
            Billing::create($test_billing);
        }
    }
}
