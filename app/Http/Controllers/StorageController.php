<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * @group Storage endpoints
 *
 * APIs for managing file storage
 */
class StorageController extends Controller
{
    protected $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Upload to storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'upload' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:1024',
        ]);
        $user = Auth::user();

        $randomSlug = Str::random(34);
        $fileData = $this->storageService->store($request->file('upload'), $user->uuid, $randomSlug);
        if (!$fileData) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Failed to upload file',
            ], 400);
        }
        
        $validated = [
            'uuid' => Str::uuid(),
            'user_uuid' => $user->uuid,
            'slug' => $randomSlug,
            'path' => $fileData['path'],
            'mimeType' => $fileData['mimeType'],
        ];

        $storage = Storage::create($validated);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => [
                'url' => $storage->path,
                'slug' => $storage->slug,
                'mimeType' => $storage->mimeType
            ],
        ]);
    }

    /**
     * Get Storage files
     * 
     * Returns files for current user, or all files if admin
     */
    public function storage(Request $request)
    {
        $user = Auth::user();
        $files = Storage::where('user_uuid', $user->uuid)->latest()->get();
        
        if ($files->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'No files found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Files retrieved successfully',
            'data' => [
                'files' => $files
            ],
        ]);
    }
}
