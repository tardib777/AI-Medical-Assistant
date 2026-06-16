<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $service) {}

    // User: my notifications
    public function index()
    {
        return response()->json(['notifications' => $this->service->userNotifications()]);
    }

    // User: mark as read
    public function markRead(Notification $notification)
    {
        $notif = $this->service->markRead($notification);
        return response()->json(['message' => 'Marked as read', 'notification' => $notif]);
    }

    // Admin: all notifications
    public function adminIndex()
    {
        return response()->json(['notifications' => $this->service->all()]);
    }

    // Admin: send notification
    public function adminSend(Request $request)
    {
        $request->validate([
            'title'   => 'required|string',
            'body'    => 'required|string',
            'type'    => 'nullable|string',
            'target'  => 'nullable|string', // all | user | patients | doctors
            'user_id' => 'nullable|exists:users,id',
        ]);
        $result = $this->service->send($request->all());
        return response()->json(['message' => 'Notification sent', 'result' => $result], 201);
    }
}
