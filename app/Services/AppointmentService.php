<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\User;

class AppointmentService
{
    public function createSlot(User $doctorUser, array $data)
    {
        return $doctorUser->doctorProfile->appointments()->create([
            'scheduled_at' => $data['scheduled_at'],
            'duration_minutes' => $data['duration_minutes'] ?? 30,
        ]);
    }

    public function myAppointments(User $doctorUser)
    {
        return $doctorUser->doctorProfile->appointments()->orderBy('scheduled_at')->get();
    }

    public function cancelSlot(User $doctorUser, int $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->doctor_id !== $doctorUser->doctorProfile->id) {
            throw new \Exception('This appointment does not belong to you');
        }

        $appointment->update(['status' => 'cancelled']);

        return $appointment->refresh();
    }

    public function listDoctors()
    {
        return DoctorProfile::with('user:id,full_name')->get();
    }

    public function availableSlots(string $doctorProfileId)
    {
        return Appointment::where('doctor_id', $doctorProfileId)
            ->where('status', 'available')
            ->orderBy('scheduled_at')
            ->get();
    }

    public function book(User $patientUser, int $appointmentId)
    {
        $updated = Appointment::where('id', $appointmentId)
            ->where('status', 'available')
            ->update(['patient_id' => $patientUser->id, 'status' => 'booked']);

        if (! $updated) {
            throw new \Exception('This appointment is no longer available');
        }

        return Appointment::find($appointmentId);
    }

    public function myBookings(User $patientUser)
    {
        return Appointment::where('patient_id', $patientUser->id)->orderBy('scheduled_at')->get();
    }

    public function cancelBooking(User $patientUser, int $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->patient_id !== $patientUser->id) {
            throw new \Exception('This appointment does not belong to you');
        }

        $appointment->update(['patient_id' => null, 'status' => 'available']);

        return $appointment->refresh();
    }
}
