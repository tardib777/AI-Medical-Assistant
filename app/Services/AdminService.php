<?php

namespace App\Services;

use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalSession;
use App\Models\Notification;

class AdminService
{
    public function dashboardStats(): array
    {
        return [
            'total_patients'     => User::where('role', 'user')->count(),
            'total_doctors'      => \App\Models\Doctor::count(),
            'total_appointments' => Appointment::count(),
            'ai_conversations'   => MedicalSession::count(),
            'pending_appts'      => Appointment::where('status', 'confirmed')->count(),
            'cancel_requests'    => Appointment::where('status', 'cancel_requested')->count(),
            'active_users'       => User::where('role', 'user')->where('is_blocked', false)->count(),
        ];
    }

    public function allUsers(array $filters = []): mixed
    {
        $q = User::where('role', 'user')->with('profile');
        if (isset($filters['status'])) {
            if ($filters['status'] === 'blocked')  $q->where('is_blocked', true);
            if ($filters['status'] === 'active')   $q->where('is_blocked', false);
        }
        if (isset($filters['search'])) {
            $s = $filters['search'];
            $q->where(fn($q) => $q->where('full_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }
        return $q->latest()->paginate(20);
    }

    public function blockUser(User $user): User
    {
        $user->update(['is_blocked' => true]);
        return $user->refresh();
    }

    public function unblockUser(User $user): User
    {
        $user->update(['is_blocked' => false]);
        return $user->refresh();
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function cancelRequests(): mixed
    {
        return Appointment::with(['user', 'doctor'])
            ->where('status', 'cancel_requested')
            ->latest()
            ->get();
    }

    public function loginLogs(): mixed
    {
        // Return recent token creations as proxy for login logs
        return \Laravel\Sanctum\PersonalAccessToken::with('tokenable')
            ->latest()
            ->take(100)
            ->get()
            ->map(fn($t) => [
                'user'       => $t->tokenable?->email,
                'created_at' => $t->created_at,
                'last_used'  => $t->last_used_at,
            ]);
    }
}
