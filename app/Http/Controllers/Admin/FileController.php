<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function download(Contribution $contribution, string $type): StreamedResponse
    {
        $field = match ($type) {
            'poster'  => 'poster_file',
            'receipt' => 'payment_receipt',
            default   => abort(404),
        };

        $path = $contribution->$field;

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'Fichier introuvable.');
        }

        $contribution->increment('downloads');

        return Storage::disk('public')->download($path, basename($path));
    }
}
