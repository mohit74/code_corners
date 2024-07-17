<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email', // Check uniqueness in the 'users' table for the 'email' field
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        $data = $request->except('password', 'confirm_password');

        $data['password']= Hash::make($request->password);

        $user = User::create($data);

        $success['token'] = $user->createToken('codeCorners')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'status' => true,
            'data' => $success,
            'message' => 'user registerd successfully'
        ];
        return response()->json($response, 200);

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // Check if validation fails
        if ($validator->fails()) {
            $response = (object) [
                'status' => false,
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400); // Return status code 400 for bad request
        }
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
                $success = (object) [
                    'token' => $user->createToken('codeCorners', ['user_id' => $user->id])->plainTextToken,
                    'name' => $user->name,
                    'user' => $user,
                ];

                $response = (object) [
                    'status' => true,
                    'data' => $success,
                    'message' => 'User logged in successfully'
                ];
                return response()->json($response, 200); 
        } else {
            $response = (object) [
                'status' => false,
                'message' => 'Invalid username or password'
            ];
            return response()->json($response, 400);
        }

    }
}

