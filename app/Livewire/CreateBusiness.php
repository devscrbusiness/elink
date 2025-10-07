<?php

namespace App\Livewire;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateBusiness extends Component
{
    public string $name = '';
    public string $custom_link = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'custom_link' => 'required|string|min:3|max:50|unique:businesses,custom_link|alpha_dash',
        ];
    }

    public function save()
    {
        $this->validate();

        Business::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'custom_link' => $this->custom_link,
        ]);

        // Redirige al dashboard. El atributo 'navigate: true' usa el router de Livewire
        // para una transición más rápida, sin recargar la página completa.
        return $this->redirect('/dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.create-business');
    }
}