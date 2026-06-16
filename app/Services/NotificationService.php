<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    // User: get own notifications
    public function userNotifications(): mixed
    {
        return Notification::where('user_id', Auth::id())
            ->orWhereNull('user_id')   // broadcast notifications
            ->latest()
            ->get();
    }

    // User: mark as read
    public function markRead(Notification $notif): Notification
    {
        $notif->update(['read_at' => now()]);
        return $notif->refresh();
    }

    // Admin: send notification
    public function send(array $data): Notification|array
    {
        $target = $data['target'] ?? 'all';

        if ($target === 'all') {
            // Broadcast: one record with user_id = null
            $notif = Notification::create([
                'user_id' => null,
                'title'   => $data['title'],
                'body'    => $data['body'],
                'type'    => $data['type'] ?? 'general',
                'status'  => 'sent',
            ]);
            return $notif;
        }

        if ($target === 'user' && isset($data['user_id'])) {
            return Notification::create([
                'user_id' => $data['user_id'],
                'title'   => $data['title'],
                'body'    => $data['body'],
                'type'    => $data['type'] ?? 'general',
                'status'  => 'sent',
            ]);
        }

        // Target a role group
        $users = User::where('role', $target)->where('is_blocked', false)->get();
        $notifs = [];
        foreach ($users as $user) {
            $notifs[] = Notification::create([
                'user_id' => $user->id,
                'title'   => $data['title'],
                'body'    => $data['body'],
                'type'    => $data['type'] ?? 'general',
                'status'  => 'sent',
            ]);
        }
        return $notifs;
    }

    // Admin: all notifications
    public function all(): mixed
    {
        return Notification::with('user')->latest()->get();
    }
}
