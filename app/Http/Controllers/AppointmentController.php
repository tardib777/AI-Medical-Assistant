<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentSlotRequest;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;
    public function __construct(AppointmentService $appointmentService){
        $this->appointmentService=$appointmentService;
    }

    public function store(StoreAppointmentSlotRequest $request){
        $appointment = $this->appointmentService->createSlot($request->user(), $request->validated());
        return response()->json(['message' => 'Appointment slot created successfully', 'appointment' => $appointment], 201);
    }

    public function myAppointments(Request $request){
        return response()->json(['appointments' => $this->appointmentService->myAppointments($request->user())]);
    }

    public function cancelSlot(Request $request, int $appointment){
        try {
            $appointment = $this->appointmentService->cancelSlot($request->user(), $appointment);
            return response()->json(['message' => 'Appointment slot cancelled successfully', 'appointment' => $appointment]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function listDoctors(){
        return response()->json(['doctors' => $this->appointmentService->listDoctors()]);
    }

    public function availableSlots(string $doctorProfile){
        return response()->json(['appointments' => $this->appointmentService->availableSlots($doctorProfile)]);
    }

    public function book(Request $request, int $appointment){
        try {
            $appointment = $this->appointmentService->book($request->user(), $appointment);
            return response()->json(['message' => 'Appointment booked successfully', 'appointment' => $appointment]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function myBookings(Request $request){
        return response()->json(['appointments' => $this->appointmentService->myBookings($request->user())]);
    }

    public function cancelBooking(Request $request, int $appointment){
        try {
            $appointment = $this->appointmentService->cancelBooking($request->user(), $appointment);
            return response()->json(['message' => 'Booking cancelled successfully', 'appointment' => $appointment]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
