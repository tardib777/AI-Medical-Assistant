<?php

namespace App\Services;

use App\Models\User;

class DoctorProfileService
{
    public function promote(array $data)
    {
        $user = User::findOrFail($data['user_id']);
        $user->assignRole('Doctor');

        return $user->doctorProfile()->create(['specialization' => $data['specialization']]);
    }
}
