<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthUserRequest;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;
     public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
     public function register(RegisterUserRequest $request)
    {
        $user = $this->userService->register($request->validated());
        return response()->json(["user" => $user,'message' => "user created successfully"], 201);
    }
    public function login(AuthUserRequest $request)
    {
        $result = $this->userService->login($request->validated());

        if ($result['status'] !== true) {
            // إعادة توجيه مع رسالة خطأ
            return back()->withErrors([
                'message' => $result['message'],
            ]);
        }
     
        return response()->json(["token" => $result['token']],200);
    }
     public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully!',
        ]);
    }

}
