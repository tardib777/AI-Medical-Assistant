<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Admin', 'Doctor', 'Patient'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    public function test_admin_promotes_doctor_who_creates_a_slot_that_a_patient_books_and_cancels(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $doctorUser = User::factory()->create();
        $patientUser = User::factory()->create();
        $patientUser->assignRole('Patient');

        $this->actingAs($admin)
            ->postJson('/api/admin/doctors', [
                'user_id' => $doctorUser->id,
                'specialization' => 'Cardiology',
            ])
            ->assertStatus(201);

        $doctorUser->refresh();
        $this->assertTrue($doctorUser->hasRole('Doctor'));
        $this->assertNotNull($doctorUser->doctorProfile);

        $slotResponse = $this->actingAs($doctorUser)
            ->postJson('/api/doctor/appointments', [
                'scheduled_at' => now()->addDay()->toDateTimeString(),
            ])
            ->assertStatus(201);

        $appointmentId = $slotResponse->json('appointment.id');

        $this->actingAs($patientUser)
            ->getJson('/api/doctors')
            ->assertStatus(200)
            ->assertJsonPath('doctors.0.specialization', 'Cardiology');

        $this->actingAs($patientUser)
            ->postJson("/api/appointments/{$appointmentId}/book")
            ->assertStatus(200)
            ->assertJsonPath('appointment.status', 'booked');

        $this->actingAs($patientUser)
            ->getJson('/api/patient/appointments')
            ->assertStatus(200)
            ->assertJsonCount(1, 'appointments');

        $this->actingAs($patientUser)
            ->deleteJson("/api/patient/appointments/{$appointmentId}")
            ->assertStatus(200)
            ->assertJsonPath('appointment.status', 'available');
    }

    public function test_a_patient_cannot_create_a_doctor_appointment_slot(): void
    {
        $patientUser = User::factory()->create();
        $patientUser->assignRole('Patient');

        $this->actingAs($patientUser)
            ->postJson('/api/doctor/appointments', [
                'scheduled_at' => now()->addDay()->toDateTimeString(),
            ])
            ->assertStatus(403);
    }
}
