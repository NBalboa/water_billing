<?php

namespace Database\Seeders;

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

        // DB::table('')->create([
        //     'username' => "admin",
        //     'first_name' => "admin",
        //     'password' => Hash::make("password"),
        //     'phone_no' => "09123456789",
        //     'address' => "my_address",
        //     'status' => "1"
        // ]);
        // DB::table('');
        //pre account username
        // DB::table('users')->insert([
        //     'username' => "admin",
        //     'first_name' => "admin",
        //     "last_name" => "account",
        //     'password' => Hash::make("password"),
        //     'phone_no' => "09123456789",
        //     'address' => "my_address",
        //     'status' => "0", // <---- check this
        //     'remember_token' => Str::random(60);
        //     'created_at' => 
        // ]);
    }
}
