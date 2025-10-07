<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromBrowser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $browserLang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        $locale = in_array($browserLang, ['en', 'es']) ? $browserLang : App::getLocale();
        App::setLocale($locale);

        return $next($request);
    }
}

