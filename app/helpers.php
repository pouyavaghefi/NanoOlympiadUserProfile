<?php

if (!function_exists('admin_asset')) {
    function admin_asset($path) {
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }

        return rtrim(env('URL_ADMIN'), '/') . '/' . ltrim($path, '/');
    }
}
