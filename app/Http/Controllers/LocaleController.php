<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch($lang, Request $request)
    {
        $available = ['es', 'en'];
        if (!in_array($lang, $available)) {
            $lang = config('app.locale');
        }

        $request->session()->put('locale', $lang);

        return redirect()->back();
    }
}