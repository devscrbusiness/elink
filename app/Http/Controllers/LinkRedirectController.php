<?php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use Illuminate\Http\Request;

class LinkRedirectController extends Controller
{
    /**
     * Redirige a la URL del enlace social y registra el clic.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(int $id)
    {
        $link = SocialLink::findOrFail($id);
        $link->clicks()->create();

        return redirect()->away($link->url);
    }
}