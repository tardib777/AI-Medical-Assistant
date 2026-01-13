<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;


class UserService
{
    public function register(array $data){
        $user=User::create(['full_name' => $data['full_name'],'gender' => $data['gender'],'birth_date' => $data['birth_date'],'email' => $data['email'],'password' => Hash::make($data['password']),'address'=>$data['address'],'phone_number'=>$data['phone_number']]);
        $profile=$user->profile()->create(['blood_type'=>$data['blood_type'],'height_cm'=>$data['height_cm'],'weight_kg'=>$data['weight_kg'],'chronic_diseases'=>$data['chronic_diseases'],'allergies'=>$data['allergies'],'current_medication'=>$data['current_medication'],'extra_info'=>$data['extra_info']]);
        return [$user,$profile];
    }
   public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'],$user->password)) {
            return [
                'status' => false,
                'message' => 'Email or password is incorrect',
            ];
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return [
            'status' => true,
            'token'    => $token,
        ];
    }
   

}

