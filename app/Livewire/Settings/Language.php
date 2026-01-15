<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Language extends Component
{
    public string $locale;

    public function mount()
    {
        // Carga el idioma del usuario o el de la configuración por defecto
        $this->locale = Auth::user()->locale ?? config('app.locale');
    }

    public function updatedLocale($value)
    {
        $user = Auth::user();
        $user->locale = $value;
        $user->save();

        // Redirige para recargar la aplicación con el nuevo idioma
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.settings.language');
    }
}