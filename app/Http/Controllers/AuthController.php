<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:250',
            'role' => 'required|string',
            'email' => 'required|string|email|max:250|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'user register succefully',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth('api')->user();
        
        if(!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'error' => 'current password is incorrect',
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'password updated successfully'
        ], 201);
    }

    public function logout(){
        auth('api')->user()->logout();
    }
}
