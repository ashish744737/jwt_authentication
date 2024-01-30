<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $login = $request->input('login');
            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
            $request->merge([$field => $login]);
            $credentials = $request->only($field, 'password');
    
            $token = Auth::attempt($credentials);
            
            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized Access',
                    'status' => 401
                ]);
            }
    
            $user = Auth::user();
            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 500
            ]);
        }
        
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'user_name' => 'required|string|regex:/\w*$/|max:255|unique:users,user_name',
                'password' => 'required|string|min:6',
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return response()->json([
                'message' => 'Your registration has been successful',
                'user' => $user,
                'status' => 200
            ]);
          
        } catch (\Throwable $th) {
            
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 500
            ]);
        }
       
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
            'status' => 200
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
                
            ],
            'status' => 200
        ]);
    }

    public function userProfile(){
        return response()->json(['user' => auth()->user(),'status' => 200]);
    }
}
