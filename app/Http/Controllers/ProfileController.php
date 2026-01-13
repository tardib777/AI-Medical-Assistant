<?php

namespace App\Http\Controllers;

use App\Http\Requests\profileRequest;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
class ProfileController extends Controller
{
    protected $profileService;
    public function __construct(ProfileService $profileService){
        $this->profileService=$profileService;
    }
    public function update(profileRequest $request){
         try {
            $profile = $this->profileService->update($request->validated());
            return response()->json([
                'message' => 'Profile updated successfully',
                'profile' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function show(){
        try {
            $profile = $this->profileService->show();
            return response()->json([
                'message' => 'Profile retrieved successfully',
                'profile' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


}
