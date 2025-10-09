<?php

namespace App\Http\Controllers;

use App\Models\WhatsappLink;
use Illuminate\Http\Request;

class WhatsappRedirectController extends Controller
{
    /**
     * Redirige a la URL de WhatsApp y registra el clic.
     */
    public function redirect(string $slug)
    {
        // Busca el enlace por el slug personalizado
        $link = WhatsappLink::where('custom_slug', $slug)->firstOrFail();

        // Registrar el clic
        $link->clicks()->create();

        // Redirige a la URL de WhatsApp (que se construye en el modelo o se guarda)
        return redirect()->away($link->url);
    }
}