<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles=[
            'Admin',
            'Doctor',
            'Patient'
        ];
        $permissions=[
            'add doctors',
            'delete doctors',
            'update doctors',
            'view doctors',
            'add appointments',
            'delete appointments',
            'update appointments',
            'view appointments',
            'take appointments',
            'cancel appointments'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $doctorRole = Role::where('name', 'Doctor')->first();
        $doctorRole->givePermissionTo([
            'add appointments',
            'delete appointments',
            'update appointments',
            'view appointments'
        ]);
        $admin=User::create([
            'full_name' => 'Dr. John Doe',
            'gender' => 'male',
            'birth_date' => '1980-01-01',
            'email' => 'dr.john.doe@example.com',
            'password' => 'john123123',
            'address' => '123 Medical St, Health City',
            'phone_number' => '0934567890'
        ]);
        
        $admin->assignRole('Admin');

    }
}
