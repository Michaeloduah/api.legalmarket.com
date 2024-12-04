<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\StorageService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }
    
    public function showProfile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "User and Profile",
            "data" => [
                "user" => $user->load("profile")
            ]
        ]);
    }
    
    public function showProfessionalProfile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "User and Profile",
            "data" => [
                "user" => $user->load("professionalProfile")
            ]
        ]);
    }
    
    public function showLawyerProfile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "User and Profile",
            "data" => [
                "user" => $user->load("lawyerProfile")
            ]
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'second_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['required', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'bio' => ['nullable', 'string', 'max:255'],
        ]);

        $fileData = $this->storageService->store($request->file('profile_picture'), Auth::user()->uuid);

        if (!$fileData) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Failed to upload file',
            ], 400);
        }
        $validated['profile_picture'] = $fileData;

        $user = Auth::user();
        $user->profile->update($validated);
        
        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "Profile updated",
            "data" => [
                "user" => $user
            ]
        ]);
    }

    public function updateProfessionalProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_title' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string', 'max:255'],
            'years_of_experience' => ['nullable', 'string', 'max:255'],
            'certifications' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->professionalProfile->update($validated);
        
        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "Professional Profile updated",
            "data" => [
                "user" => $user
            ]
        ]);
    }

    public function updateLawyerProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bar_certificate' => ['required', 'string', 'max:255'],
            'bar_association' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255'],
            'license_issue_date' => ['required', 'string', 'max:255'],
            'license_expiry_data' => ['required', 'string', 'max:255'],
            'pratice_areas' => ['required', 'string', 'max:255'],
            'years_of_experience' => ['required', 'string', 'max:255'],
            'law_firm' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'availability' => ['required', 'string', 'max:255'],
            'graduation_year' => ['required', 'string', 'max:255'],
            'professional_bio' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->lawyerProfile->update($validated);
        
        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "Lawyer Profile updated",
            "data" => [
                "user" => $user
            ]
        ]);
    }
}
