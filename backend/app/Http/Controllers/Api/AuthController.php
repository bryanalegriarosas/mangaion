<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginResquest;
use App\Http\Requests\Auth\RegisterResquest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 📝 Registro
     */
    public function register(RegisterResquest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 🎭 Asignar rol por defecto
        $role = Role::where('name', 'user')->first();

        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered',
            'token' => $token,
            'user' => $user->load('roles'),
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * 🔐 Login
     */
    public function login(LoginResquest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user->load('roles'),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 👤 Usuario actual
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load('roles'),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 🚪 Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ], JsonResponse::HTTP_OK);
    }
}
