<?php

namespace App\Http\Livewire;

use App\Models\Business; // Asegúrate de que tu modelo de empresa esté en esta ruta
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BusinessPublic extends Component
{
    /**
     * La instancia del modelo de la empresa.
     *
     * @var \App\Models\Business
     */
    public Business $business;

    /**
     * Monta el componente con la instancia de la empresa.
     */
    public function mount(Business $business): void
    {
        $this->business = $business;
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render(): View
    {
        return view('livewire.business-public');
    }
}
