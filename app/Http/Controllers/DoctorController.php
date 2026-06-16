<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use App\Services\DoctorService;
class DoctorController extends Controller
{
    protected DoctorService $doctorService;
    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }
    public function store(DoctorRequest $request) {
        $doctor = $this->doctorService->create($request->validated());
        return response()->json($doctor);
    }
    public function update(DoctorRequest $request,$doctor_id) {
        $doctor = Doctor::findOrFail($doctor_id);
        $doctor = $this->doctorService->update($doctor, $request->validated());
        return response()->json($doctor);
    }
    public function destroy($doctor_id) {
        $doctor = Doctor::findOrFail($doctor_id);
        $doctor->delete();
        return response()->json([
            'message' => 'Doctor deleted successfully'
        ]);
    }
}
