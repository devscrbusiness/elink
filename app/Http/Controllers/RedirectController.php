<?php

namespace App\Http\Controllers;

use App\Models\WhatsappLink;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function whatsapp(string $slug)
    {
        $link = WhatsappLink::where('custom_slug', $slug)->firstOrFail();

        return redirect()->away($link->url);
    }
}