<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    /**
     * Create appointment
     */
    public function create(Doctor $doctor, array $data): Appointment
    {
        $appointmentTime = Carbon::parse(
            $data['appointment_time']
        );

        if ($appointmentTime->isPast()) {
            throw ValidationException::withMessages([
                'appointment_time' => [
                    'Appointment must be in the future.'
                ]
            ]);
        }

        if( $doctor->appointments()->where('appointment_time', $appointmentTime)->exists() ) {
            throw ValidationException::withMessages([
                'appointment_time' => [
                    'You already have an appointment at this time.'
                ]
            ]);
        }
        else{
            return $doctor->appointments()->create([
                'appointment_time' => $appointmentTime,
                'appointment_location' => $data['appointment_location'],
                'status' => 'available',
            ]);
        }
        
    }

    /**
     * Update appointment
     */
    public function update(
        Appointment $appointment,
        array $data
    ): Appointment {

        $this->ensureAppointmentCanBeModified(
            $appointment
        );

        $appointmentTime = Carbon::parse(
            $data['appointment_time']
        );

        if ($appointmentTime->isPast()) {
            throw ValidationException::withMessages([
                'appointment_time' => [
                    'Appointment must be in the future.'
                ]
            ]);
        }
        if( Appointment::where('doctor_id', $appointment->doctor_id)
        ->where('appointment_time', $appointmentTime)
        ->where('id', '!=', $appointment->id)
        ->exists() ) {
            throw ValidationException::withMessages([
                'appointment_time' => [
                    'You already have an appointment at this time.'
                ]
            ]);
        }
        else{
            $appointment->update([
                'appointment_time' => $appointmentTime,
                'appointment_location' => $data['appointment_location'],
            ]);

            return $appointment->fresh();
        }
       
    }

    /**
     * Delete appointment
     */
    public function delete(
        Appointment $appointment
    ): bool {

        $this->ensureAppointmentCanBeModified(
            $appointment
        );
        $appointment->status='cancelled';
        return $appointment->save();
    }

    /**
     * Business Rule
     */
    private function ensureAppointmentCanBeModified(
        Appointment $appointment
    ): void {

        if (
            $appointment->appointment_time->isPast()
        ) {
            throw ValidationException::withMessages([
                'appointment' => [
                    'Past appointments cannot be modified.'
                ]
            ]);
        }

        $booking = $appointment->booking;

        if (! $booking) {
            return;
        }

        if (
            in_array(
                $booking->status,
                ['scheduled', 'completed']
            )
        ) {
            throw ValidationException::withMessages([
                'appointment' => [
                    'Booked appointments cannot be modified.'
                ]
            ]);
        }
    }
}