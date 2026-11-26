<?php

namespace App\Support;

class Media
{
    public static function url(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }

        return '/'.ltrim($path, '/');
    }
}
