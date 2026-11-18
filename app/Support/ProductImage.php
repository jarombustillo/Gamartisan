<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores product images on the disk named by UPLOADS_DISK.
 *
 * - "uploads" (default): public/uploads on the server's own disk. Fine locally,
 *   but Laravel Cloud wipes it on every deploy.
 * - a bucket disk: on Laravel Cloud, set UPLOADS_DISK to the bucket's "disk name"
 *   (the bucket must be public so product images get public URLs).
 *
 * The saved value stays compatible with existing rows: "/uploads/products/x.jpg"
 * for the local disk, or the bucket's full URL for s3.
 */
class ProductImage
{
    public static function disk(): string
    {
        return config('filesystems.uploads_disk', 'uploads');
    }

    public static function store(UploadedFile $file): string
    {
        // extension() is detected from the file's content. The client-supplied
        // extension was used before, which let "picture.html" be served as a web page.
        $name = time() . '_' . Str::random(16) . '.' . ($file->extension() ?: 'jpg');
        // No per-file visibility: Laravel Cloud buckets (Cloudflare R2) reject object ACLs,
        // and the local "uploads" disk is already public by its config.
        $path = Storage::disk(self::disk())->putFileAs('products', $file, $name);

        return self::disk() === 'uploads'
            ? '/uploads/' . $path
            : Storage::disk(self::disk())->url($path);
    }

    public static function delete(?string $value): void
    {
        if (!$value) {
            return;
        }

        if (str_starts_with($value, '/uploads/')) {
            Storage::disk('uploads')->delete(substr($value, strlen('/uploads/')));
            return;
        }

        if (self::disk() !== 'uploads') {
            $base = rtrim(Storage::disk(self::disk())->url(''), '/') . '/';
            if (str_starts_with($value, $base)) {
                Storage::disk(self::disk())->delete(substr($value, strlen($base)));
            }
        }
    }

    public static function url(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        return preg_match('#^https?://#i', $value) ? $value : asset(ltrim($value, '/'));
    }
}
