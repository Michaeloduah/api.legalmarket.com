<?php

namespace App\Services;

use App\Models\Storage;
use Illuminate\Support\Str;

class StorageService
{
    /**
     * Create a new class instance.
     */
    public function store($file, $user_uuid)
    {
        $uuid = Str::uuid();
        $name = Str::random(39) . '.' . $file->getClientOriginalExtension();

        $mimeType = $file->getMimeType();
        $directory = in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']) ? 'thumbnails' : 'files';
        $filePath = $file->storeAs($directory, $name, 'public');

        Storage::create([
            'uuid' => $uuid,
            'user_uuid' => $user_uuid,
            'name' => $name,
            'path' => $filePath,
            'mimeType' => $mimeType,
        ]);

        $url = env('APP_URL') . "/storage/" . $filePath;
        return  $url;
    }
}
