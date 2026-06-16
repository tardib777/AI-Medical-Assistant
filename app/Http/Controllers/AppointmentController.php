<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Http\Requests\AppointmentRequest;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    public function index() {
        $doctor = Auth::user()->doctor;
        $appointments = $doctor->appointments()->get();

        return response()->json($appointments);
    }
    public function store(AppointmentRequest $request,AppointmentService $appointmentService) {
        $doctor = Auth::user()->doctor;

        $appointment = $appointmentService->create(
            $doctor,
            $request->validated()
        );

        return response()->json($appointment);
    }
    public function update(AppointmentRequest $request,$appointment_id,AppointmentService $appointmentService) {
        $appointment = Appointment::findOrFail($appointment_id);
        $appointment = $appointmentService->update(
            $appointment,
            $request->validated()
        );

        return response()->json($appointment);
    }
    public function destroy($appointment_id,AppointmentService $appointmentService) {
        $appointment = Appointment::findOrFail($appointment_id);
        $appointmentService->delete($appointment);
        return response()->json([
            $appointment,
            'message' => 'Appointment deleted successfully'
        ]);
    }

}
