<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(protected AdminService $service) {}

    public function dashboard()
    {
        return response()->json(['stats' => $this->service->dashboardStats()]);
    }

    public function users(Request $request)
    {
        return response()->json($this->service->allUsers($request->only(['status', 'search'])));
    }

    public function blockUser(User $user)
    {
        return response()->json(['message' => 'User blocked', 'user' => $this->service->blockUser($user)]);
    }

    public function unblockUser(User $user)
    {
        return response()->json(['message' => 'User unblocked', 'user' => $this->service->unblockUser($user)]);
    }

    public function deleteUser(User $user)
    {
        $this->service->deleteUser($user);
        return response()->json(['message' => 'User deleted']);
    }

    public function cancelRequests()
    {
        return response()->json(['cancel_requests' => $this->service->cancelRequests()]);
    }

    public function loginLogs()
    {
        return response()->json(['logs' => $this->service->loginLogs()]);
    }
}
