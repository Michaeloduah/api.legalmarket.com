<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\LawyerProfile;
use App\Models\ProfessionalProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required'],
        ]);

        $validated["uuid"] = Str::uuid();

        // Create the user
        $user = User::create([
            'uuid' => $validated['uuid'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Profile::create([
            "user_uuid" => $user->uuid,
        ]);

        ProfessionalProfile::create([
            "user_uuid" => $user->uuid,
        ]);

        if ($user->role === "lawyer") {
            LawyerProfile::create([
                "user_uuid" => $user->uuid,
            ]);
        } else {
            $lawyerprofile = null;
        };


        // Dispatch the registered event
        event(new Registered($user));

        // Return a JSON response with the user details and token
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'profile' => $user->load("profile"),
            'Professional Profile' => $user->load("professionalprofile"),
        ], 201);
    }
}
