<?php

namespace App\Services;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
class ProfileService{
    public function update(array $data)
    {
        $profile=Auth::user()->profile;

        if (!$profile) {
            throw new \Exception('You do not have a medical file, please create your medical file first');
        }
        $profile->update($data);
        return $profile->refresh();
    }
    public function show(){
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
             throw new \Exception('You do not have a medical file, please create your medical file first');

        }

        return [
            'full_name' => $user->full_name,
            'gender' => $user->gender,
            'birth_date' => $user->birth_date,
            'email' => $user->email,
            'phone_number' =>$user->phone_number,
            'blood_type' => $profile->blood_type,
            'height_cm' =>$profile->height_cm,
            'weight_kg' =>$profile->weight_kg,
            'chronic_diseases' => $profile->chronic_diseases,
            'allergies' => $profile->allergies,
            'current_medication' => $profile->current_medication,
            'extra_info' =>$profile->extra_info
        ];
    }
}