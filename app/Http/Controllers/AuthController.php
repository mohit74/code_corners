<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signUp()
    {
        return view('auth.sign-up');
    }

    public function signIn()
    {
        return view('auth.sign-in');
    }

    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        $data = $request->except('_token', 'password');
                       
        $data['password'] = Hash::make($request->password);

        // Create the user
        User::create($data);

        return redirect()->route('signIn')->with('success', 'Registration Successfully');
    }

    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'

        ]);

        // Attempt to log in the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Return a response
            return redirect()->route('blogs.index')->with('success', 'Login Successfully');
        } else {
            // Return a response
            return redirect()->back()->with('success', 'Invalid email or password');

        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('signIn')->with('success', 'Logout Successfully');
    }
}
