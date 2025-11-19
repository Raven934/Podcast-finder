<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        try {
            $user = User::create($request->validated());
            
            return response()->json([
                "message" => "User registered successfully", 
                "user" => $user
            ], 201);
            
        } catch (\Exception) {
            return response()->json([
                'error' => 'Database Error',
                'message' => 'Failed to create user. Email might already exist.',
                'details' => 'Please try with a different email address.'
            ], 422);
            
    };
}


     public function login(LoginRequest $request){
        try {
            if(!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'error' => 'Authentication Failed',
                    'message' => 'Invalid email or password.',
                    'details' => 'Please check your credentials and try again.'
                ], 401);
            }
            
            $user = User::where('email', $request->email)->firstOrFail();
            
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ], 200);
            
        } catch (\Exception) {
            return response()->json([
                'error' => 'User Not Found',
                'message' => 'User account could not be found.',
                'details' => 'Please check your email address.'
            ], 404);
            
        };
     }
     public function logout(Request $request){
        try {
            if (!$request->user() || !$request->user()->currentAccessToken()) {
                return response()->json([
                    'error' => 'Not Authenticated',
                    'message' => 'No active session found.',
                    'details' => 'You are already logged out.'
                ], 401);
            }
            
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'message' => 'Logout successful',
                'details' => 'You have been successfully logged out.'
            ], 200);
            
        } catch (\Exception) {
            return response()->json([
                'error' => 'Logout Failed',
                'message' => 'An error occurred during logout.',
                'details' => 'Please try again or clear your browser cache.'
            ], 500);
        }
     }
}
