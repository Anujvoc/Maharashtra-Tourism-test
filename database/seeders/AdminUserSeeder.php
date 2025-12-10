<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'], // unique condition
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'registration_id' => 'ADMIN001',
                'image' => null,
                'phone' => '9999999999',
                'email' => 'admin@test.com',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('Admin@123'),
                'aadhar' => '123456789018',
                'is_email_verified' => true,
                'email_verified_at' => Carbon::now(),
                'is_phone_verified' => true,
                'phone_verified_at' => Carbon::now(),
                'is_aadhar_verified' => true,
                'aadhar_verified_at' => Carbon::now(),
                'last_otp_sent_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
