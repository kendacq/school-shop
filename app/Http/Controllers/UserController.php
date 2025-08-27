<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'student_id' => ['required'],
            'password' => ['required'],
        ]);

        if (auth()->attempt(['student_id' => $incomingFields['student_id'], 'password' => $incomingFields['password']])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return "Wrong Email/Password";
    }


    public function signup(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required'],
            'student_id' => ['required', Rule::unique('users', 'student_id')],
            'password' => ['required', 'min:8', 'max:50', 'confirmed'],
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        User::create($incomingFields);

        return "Sign Up:" .
            "\nName: " . $incomingFields['name'] .
            "\nStudent ID: " . $incomingFields['student_id'] .
            "\nPassword: " . $incomingFields['password'];
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }
}
