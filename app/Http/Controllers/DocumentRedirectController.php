<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DocumentRedirectController extends Controller
{
    /**
     * Registra un clic para el documento y redirige al archivo.
     */
    public function redirect(Document $document): RedirectResponse
    {
        $document->clicks()->create();

        return redirect(Storage::url($document->path));
    }
}