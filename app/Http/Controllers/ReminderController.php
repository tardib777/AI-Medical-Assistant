<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Services\ReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function __construct(protected ReminderService $service) {}

    public function index()
    {
        return response()->json(['reminders' => $this->service->all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string',
            'dose'      => 'nullable|string',
            'time'      => 'required|string',
            'frequency' => 'in:daily,weekly,monthly',
            'notes'     => 'nullable|string',
        ]);
        $reminder = $this->service->create($data);
        return response()->json(['message' => 'Reminder created', 'reminder' => $reminder], 201);
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $data = $request->validate([
            'name'      => 'sometimes|string',
            'dose'      => 'nullable|string',
            'time'      => 'sometimes|string',
            'frequency' => 'in:daily,weekly,monthly',
            'notes'     => 'nullable|string',
        ]);
        $reminder = $this->service->update($reminder, $data);
        return response()->json(['message' => 'Reminder updated', 'reminder' => $reminder]);
    }

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $this->service->delete($reminder);
        return response()->json(['message' => 'Reminder deleted']);
    }

    // Admin: all reminders
    public function adminIndex()
    {
        $reminders = Reminder::with('user')->latest()->get();
        return response()->json(['reminders' => $reminders]);
    }
}
