<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoteDoctorRequest;
use App\Services\DoctorProfileService;

class DoctorProfileController extends Controller
{
    protected $doctorProfileService;
    public function __construct(DoctorProfileService $doctorProfileService){
        $this->doctorProfileService=$doctorProfileService;
    }
    public function store(PromoteDoctorRequest $request){
        try {
            $profile = $this->doctorProfileService->promote($request->validated());
            return response()->json([
                'message' => 'User promoted to Doctor successfully',
                'profile' => $profile
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
