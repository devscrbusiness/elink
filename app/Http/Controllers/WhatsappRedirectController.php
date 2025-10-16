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

    /**
     * Redirige a la URL de WhatsApp por ID y registra el clic.
     * Esto se usa para enlaces que no tienen un slug personalizado.
     */
    public function redirectById(int $id)
    {
        $link = WhatsappLink::findOrFail($id);

        // Registrar el clic
        $link->clicks()->create();

        return redirect()->away($link->url);
    }
}