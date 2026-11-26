<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class Uploads
{
    public static function image(?UploadedFile $file, ?string $current): ?string
    {
        if (! $file) {
            return $current;
        }

        $dir = public_path('images/uploads');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $safe = preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName()) ?: 'photo.jpg';
        $name = time().'-'.$safe;
        $file->move($dir, $name);

        return '/images/uploads/'.$name;
    }

    public static function media(?UploadedFile $file, ?string $current = null): ?string
    {
        if (! $file) {
            return $current;
        }

        $dir = public_path('videos/uploads');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $safe = preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName()) ?: 'clip.mp4';
        $name = time().'-'.$safe;
        $file->move($dir, $name);

        return '/videos/uploads/'.$name;
    }
}
