<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ImageService
{
    public function store($file, string $folder = 'images', bool $isPublic = false)
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $uuid = Str::uuid()->toString();
        $cleanName = $uuid . '.' . $extension;

        // تحقق من الحجم
        $size = $file->getSize();
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($size > $maxFileSize) {
            Log::warning("File too large: {$cleanName}, Size: {$size}");
            throw new HttpException(413, 'File size exceeds the allowed limit');
        }

        // تحقق من النوع
        $realMime = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/png'];
        if (!in_array($realMime, $allowedMimes)) {
            Log::warning("Invalid MIME type: {$realMime} for {$cleanName}");
            throw new HttpException(403, 'Invalid file type');
        }

        // تحقق من الامتداد
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($extension, $allowedExtensions)) {
            Log::warning("Invalid file extension for {$cleanName}");
            throw new HttpException(403, 'Invalid file extension');
        }

        // تحقق من الأبعاد
        $dimensions = @getimagesize($file->getRealPath());
        if (!$dimensions) {
            Log::warning("Invalid image dimensions for {$cleanName}");
            throw new HttpException(403, 'Invalid image dimensions');
        }

        [$width, $height] = $dimensions;
        if ($width > 8000 || $height > 8000) {
            throw new HttpException(403, 'Image dimensions too large');
        }

        // تخزين الملف
        // $storagePath = $isPublic ? 'public/' . $folder : $folder;
        $path = $file->storeAs($folder, $cleanName,$isPublic ? 'public' : 'local');

        Log::info("Image stored successfully: {$path}");

        return [
            'path' => $path,
            'filename' => $cleanName,
            'original_name' => $originalName,
            'url' => $this->getPublicUrl($path)
        ];
    }

    /**
     * إرجاع الرابط العام للملف المخزن
     */
    public function getPublicUrl(string $path): ?string
    {
        if (str_starts_with($path, 'public/')) {
            return Storage::url($path);
        }

        return null;
    }

    /**
     * حذف ملف من التخزين
     */
    public function delete(?string $path , string $disk = 'local'): bool
    {
        if (!$path) {
            return false;
        }

        if (Storage::disk($disk)->exists($path)) {
        Storage::disk($disk)->delete($path);
        Log::info("File deleted successfully: {$path}");
        return true;
        }

        Log::warning("File not found for deletion: {$path}");
        return false;
    }
    
    public function replace(?string $oldPath, $newFile, string $folder = 'images', bool $isPublic = false)
    {
        $disk = $isPublic ? 'public' : 'local';

        if ($oldPath) {
            $this->delete($oldPath, $disk);
        }

        return $this->store($newFile, $folder, $isPublic);
    }

}
