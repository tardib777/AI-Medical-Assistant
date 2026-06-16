<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'full_name' => 'Admin User',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'email' => 'admin@example.com',
            'password' => 'ad123123',
            'address' => 'Admin Address',
            'phone_number' => '0934567890',
            'role' => 'admin',
        ]);
    }
}
