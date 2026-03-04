<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    public function generateThumbnail(string $storedPath): ?string
    {
        try {
            $disk = Storage::disk('public');
            $fullPath = $disk->path($storedPath);

            if (!file_exists($fullPath)) {
                return null;
            }

            $directory = pathinfo($storedPath, PATHINFO_DIRNAME);
            $filename = pathinfo($storedPath, PATHINFO_BASENAME);
            $thumbnailDir = $directory . '/thumbnails';
            $thumbnailPath = $thumbnailDir . '/' . $filename;

            $disk->makeDirectory($thumbnailDir);

            $image = Image::read($fullPath);
            $image->scaleDown(400, 400);

            $extension = strtolower(pathinfo($storedPath, PATHINFO_EXTENSION));
            $quality = in_array($extension, ['jpg', 'jpeg', 'webp']) ? 85 : null;

            if ($quality) {
                $encoded = $image->encodeByExtension($extension, quality: $quality);
            } else {
                $encoded = $image->encodeByExtension($extension);
            }

            $disk->put($thumbnailPath, (string) $encoded);

            return $thumbnailPath;
        } catch (\Throwable $e) {
            Log::warning('Thumbnail generation failed: ' . $e->getMessage(), [
                'path' => $storedPath,
            ]);

            return null;
        }
    }
}
