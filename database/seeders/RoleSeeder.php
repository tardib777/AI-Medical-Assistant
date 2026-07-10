<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Admin', 'Doctor', 'Patient'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'full_name' => 'System Admin',
                'gender' => 'male',
                'birth_date' => '1990-01-01',
                'password' => Hash::make('password'),
                'address' => 'N/A',
                'phone_number' => '00000000',
            ]
        );

        $admin->syncRoles(['Admin']);
    }
}
