<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    /**
     * Create doctor
     */
    public function create(array $data): Doctor
    {
        $user=User::create([
            'full_name' => $data['full_name'],
            'gender' => $data['gender'],
            'birth_date' => $data['birth_date'],
            'email' => $data['email'],
            'password' => $data['password'],
            'address' => $data['address'],
            'phone_number' => $data['phone_number']
        ]);
        $user->assignRole('Doctor');
        $doctor = $user->doctor()->create([
            'specialization' => $data['specialization'],
            'qualification' => $data['qualification'],
            'experience_years' => $data['experience_years'],
            'license_number' => $data['license_number'],
            'bio' => $data['bio'],
            'profile_picture' => $data['profile_picture']
        ]);
        return $doctor;
    }
    public function update(Doctor $doctor, array $data): Doctor
    {
        $doctor->user->update([
            'full_name' => $data['full_name'],
            'gender' => $data['gender'],
            'birth_date' => $data['birth_date'],
            'email' => $data['email'],
            'address' => $data['address'],
            'phone_number' => $data['phone_number']
        ]);
        return $doctor;
    }
}