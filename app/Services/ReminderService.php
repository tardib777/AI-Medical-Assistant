<?php

namespace App\Services;

use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;

class ReminderService
{
    public function all(): mixed
    {
        return Auth::user()->reminders()->latest()->get();
    }

    public function create(array $data): Reminder
    {
        return Auth::user()->reminders()->create($data);
    }

    public function update(Reminder $reminder, array $data): Reminder
    {
        $reminder->update($data);
        return $reminder->refresh();
    }

    public function delete(Reminder $reminder): void
    {
        $reminder->delete();
    }
}
