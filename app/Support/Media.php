<?php

namespace App\Support;

class Media
{
    /**
     * Public URL for a photo stored by the admin (e.g. "uploads/products/x.jpg").
     * Prefers the .webp copy when the admin folder is reachable and it exists.
     */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = ltrim($path, '/');
        $root = config('mazz.media_path');

        if ($root) {
            $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
            if ($webp !== $path && is_file(rtrim($root, '/\\').DIRECTORY_SEPARATOR.$webp)) {
                $path = $webp;
            }
        }

        return config('mazz.media_url').'/'.str_replace('%2F', '/', rawurlencode($path));
    }

    public static function price(float|string|null $amount): string
    {
        return 'Rs. '.number_format((float) $amount, 0);
    }
}
