<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ១. មុខងារចុះឈ្មោះ (Register)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        // បង្កើត User ថ្មីក្នុង Database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // បំប្លែង Password ជាកូដសម្ងាត់
        ]);

        // បង្កើត Token សម្រាប់ User នេះ
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'ចុះឈ្មោះជោគជ័យ!',
            'access_token' => $token, // ផ្ញើ Token ទៅឱ្យក្រុម UI
            'user' => $user
        ], 201);
    }

    // ២. មុខងារចូលគណនី (Login)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // ស្វែងរក User តាម Email
        $user = User::where('email', $request->email)->first();

        // ពិនិត្យមើលថា User មានអត់ ហើយ Password ត្រូវអត់
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'អ៊ីមែល ឬ លេខសម្ងាត់មិនត្រឹមត្រូវទេ!'
            ], 401);
        }

        // បើត្រូវ បង្កើត Token ឱ្យថ្មី
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'ចូលគណនីជោគជ័យ!',
            'access_token' => $token, // ផ្ញើ Token ទៅឱ្យក្រុម UI
            'user' => $user
        ], 200);
    }
}