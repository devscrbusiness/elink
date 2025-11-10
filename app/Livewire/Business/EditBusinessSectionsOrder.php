<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditBusinessSectionsOrder extends Component
{
    public Business $business;
    public array $sections = [];

    private function allSections(): array
    {
        return [
            ['id' => 'whatsapp', 'name' => __('edit-business.section_whatsapp')],
            ['id' => 'sms', 'name' => __('edit-business.section_sms')],
            ['id' => 'social_networks', 'name' => __('edit-business.section_social_networks')],
            ['id' => 'mails', 'name' => __('edit-business.section_mails')],
            ['id' => 'websites', 'name' => __('edit-business.section_websites')],
            ['id' => 'others', 'name' => __('edit-business.section_others')],
            ['id' => 'documents', 'name' => __('edit-business.section_documents')],
            ['id' => 'location', 'name' => __('edit-business.section_location')],
        ];
    }

    public function mount(Business $business): void
    {
        $this->business = $business;
        $this->loadSections();
    }

    /**
     * Carga las secciones en el orden guardado o en el orden por defecto.
     */
    public function loadSections(): void
    {
        $allSections = collect($this->allSections())->keyBy('id');
        $orderedIds = $this->business->section_order ?? $allSections->keys()->toArray();

        // Asegura que todas las secciones estén presentes, incluso si se añaden nuevas en el futuro.
        $this->sections = collect($orderedIds)
            ->merge($allSections->keys()->diff($orderedIds))
            ->map(fn ($id) => $allSections->get($id))
            ->filter() // Elimina cualquier nulo si una clave antigua fue eliminada.
            ->values()
            ->toArray();
    }

    /**
     * Se llama cuando el usuario reordena las secciones en el frontend.
     * Guarda el nuevo orden en la base de datos.
     */
    public function updateSectionOrder(array $orderedIds): void
    {
        $this->business->update(['section_order' => $orderedIds]);

        $this->loadSections();
    }

    public function render(): View
    {
        return view('livewire.edit-business-sections-order');
    }
}