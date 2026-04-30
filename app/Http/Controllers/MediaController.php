<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        $path = trim($path, '/');

        abort_if($path === '' || str_contains($path, '..'), 404);

        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        return $disk->response($path, null, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
