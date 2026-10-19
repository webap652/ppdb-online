<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class Controller
{
    /**
     * Simpan file unggahan ke disk public, hapus file lama bila ada.
     */
    protected function storeUpload(UploadedFile $file, string $dir, ?string $old = null): string
    {
        $this->deleteUpload($old);

        return $file->store($dir, 'public');
    }

    protected function deleteUpload(?string $path): void
    {
        // Aset bawaan di public/images tidak ikut dihapus
        if ($path && ! Str::startsWith($path, ['images/', 'http://', 'https://'])) {
            Storage::disk('public')->delete($path);
        }
    }
}
