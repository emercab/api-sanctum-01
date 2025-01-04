<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function register(Request $request): JsonResponse 
  {
    $data = $request->validate([
      'name' => 'required|max:255',
      'email' => 'required|email|unique:users',
      'password' => 'required|confirmed',
    ]);

    $user = User::create($data);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'message' => 'User created successfully',
      'token' => $token,
      'status' => 201,
      'user' => $user,
    ], 201);
  }


  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      return response()->json([
        'message' => 'The provided credentials are incorrect.',
        'status' => 401,
      ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'message' => "User {$user->name} logged in successfully",
      'token' => $token,
      'status' => 200,
      'user' => $user,
    ], 200);
  }


  public function logout(Request $request): JsonResponse
  {
    $request->user()->tokens()->delete();

    return response()->json([
      'message' => 'User logged out successfully',
      'status' => 200,
    ], 200);
  }
}
