<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    // Login user and return JWT token
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    // // Register new user and return JWT token
    public function register(RegisterRequest $request)
    {
        // إنشاء مستخدم جديد بعد التحقق من البيانات
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // إنشاء التوكن مباشرة بعد التسجيل
        $token = auth('api')->login($user);


        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 201);
    }

    // Logout user
    public function logout()
    {

        auth('api')->logout();

        return response()->json(['message' => 'successfully logged out!']);
    }

    // Get authenticated user
    public function profile()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'message' => 'User retrieved successfully',
            'status' => 200,
            'success' => true,
            'user' => $user
        ]);
    }

    // Refresh JWT token
    public function refresh()
    {
        $refreshToken = auth('api')->refresh();

        return response()->json([
            'message' => 'Token refreshed successfully',
            'status' => 200,
            'access_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
