<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
    * User Login
    */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
             // email Example: user@email.com
            "email" => ["required", "string", "email"],
             // password Example: password
            "password" => ["required", "string"],
        ]);
 
        if(!Auth::attempt($validated)){
            return response()->json([
                "status" => 401,
                "success" => false,
                "message" =>  "Bad Credentials"
            ], 401);
        }else{
            $user = User::where("email", $request["email"])->first();
            $token = $user->createToken("device_token")->plainTextToken;
 
            return response()->json([
                "status" => 200,
                "success" => true,
                "message" => "Welcome back!",
                "token" => $token,
                "data"=>[
                    "user" => $user,
                    "profile" => $user->load("profile"),
                    "professional_profile" => $user->load("professionalProfile"),
                ]
            ]);
        }
    }
 
    /**
     * User Logout
     */
    public function destroy(): JsonResponse
    {
        $user = Auth::user();
        $user->token()->delete();
 
        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "Session terminated"
        ]);
    }
}
