<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'personal_id' => ['required'],
            'password' => ['required'],
        ]);

        if (auth()->attempt(['personal_id' => $incomingFields['personal_id'], 'password' => $incomingFields['password']])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return response()->json(['message' => 'Wrong Email/Password']);
    }


    public function signup(Request $request)
    {
        $incomingFields = $request->validate([
            'personal_id' => ['nullable', Rule::unique('users', 'personal_id')],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'role' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', column: 'email')],
            'contact_no' => ['required', Rule::unique('users', 'contact_no')],
            'local_address' => ['required'],
            'password' => ['required', 'min:8', 'max:50', 'confirmed'],
        ], [
            'personal_id.unique' => 'This ID is already registered.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'role.required' => 'Please select a role.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'contact_no.required' => 'Contact number is required.',
            'contact_no.unique' => 'This contact number is already in use.',
            'local_address.required' => 'Local address is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 50 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        if ($incomingFields['role'] === 'guest') {
            do {
                $randomId = 'G' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            } while (User::where('personal_id', $randomId)->exists());

            $incomingFields['personal_id'] = $randomId;
        }

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        User::create($incomingFields);

        return response()->json(['message' => 'Signup successful!']);
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }
}
