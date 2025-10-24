<?php

namespace App\Livewire\Business;

use App\Models\Location;
use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditBusinessLocation extends Component
{
    public Business $business;
    public bool $isAdminEditing = false;
    public ?User $user;

    public array $locations = [];
    public array $editingLocation = [];
    public ?int $editingIndex = null;

    public function mount(Business $business, bool $isAdminEditing = false)
    {
        $user = Auth::user();
        // Permitir el acceso si el usuario es administrador (role=1) O si es el dueño de la empresa.
        if ($user->role !== 1 && $business->user_id !== $user->id && !$isAdminEditing) {
            abort(403, 'No tienes permiso para editar esta empresa.');
        }

        $this->isAdminEditing = $isAdminEditing || ($user->role === 1 && $business->user_id !== $user->id);

        $this->user = $business->user;
        $this->business = $business;

        // Carga las ubicaciones existentes en el array
        $this->locations = $this->business->locations->map(function ($location) {
            return [
                'id' => $location->id,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'detail' => $location->detail,
            ];
        })->toArray();

        // Si no hay ubicaciones, añade una por defecto para que el usuario pueda empezar
        $this->prepareNewLocation();
    }

    protected function rules(): array
    {
        return [
            'editingLocation.latitude' => ['required', 'numeric', 'between:-90,90'],
            'editingLocation.longitude' => ['required', 'numeric', 'between:-180,180'],
            'editingLocation.detail' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'editingLocation.latitude' => __('edit-business.location_latitude_label'),
            'editingLocation.longitude' => __('edit-business.location_longitude_label'),
            'editingLocation.detail' => __('edit-business.location_detail_label'),
        ];
    }

    public function selectForEditing(int $index): void
    {
        $this->editingIndex = $index;
        $this->editingLocation = $this->locations[$index];
        $this->dispatch('location-selected', latitude: $this->editingLocation['latitude'], longitude: $this->editingLocation['longitude']);
    }

    public function prepareNewLocation(): void
    {
        $this->editingIndex = null;
        $this->editingLocation = [
            'id' => null,
            'latitude' => -0.2224093, // Coordenadas por defecto (Quito)
            'longitude' => -78.5335029,
            'detail' => '',
        ];
        $this->dispatch('location-selected', latitude: $this->editingLocation['latitude'], longitude: $this->editingLocation['longitude']);
    }

    /**
     * Guarda la ubicación actual (nueva o en edición) en la base de datos.
     */
    public function saveLocation(): void
    {
        $this->validate();

        $this->business->locations()->updateOrCreate(
            ['id' => $this->editingLocation['id'] ?? null],
            [
                'latitude' => $this->editingLocation['latitude'],
                'longitude' => $this->editingLocation['longitude'],
                'detail' => $this->editingLocation['detail'],
            ]
        );

        session()->flash('message', is_null($this->editingIndex) ? __('edit-business.social_link_create_success') : __('edit-business.social_link_update_success'));

        // Recarga las ubicaciones desde la BD y resetea el formulario
        $this->mount($this->business, $this->isAdminEditing);
        $this->prepareNewLocation();
    }
    
    public function save()
    {
        $this->validate();

        $currentLocationIds = collect($this->locations)->pluck('id')->filter()->all();

        // Elimina las ubicaciones que se quitaron de la interfaz
        $this->business->locations()->whereNotIn('id', $currentLocationIds)->delete();

        // Actualiza o crea las ubicaciones
        foreach ($this->locations as $locationData) {
            $this->business->locations()->updateOrCreate(
                ['id' => $locationData['id'] ?? null], // Busca por ID o prepara para crear
                [
                    'latitude' => $locationData['latitude'],
                    'longitude' => $locationData['longitude'],
                    'detail' => $locationData['detail'],
                ]
            );
        }

        session()->flash('message', __('edit-business.location_update_success'));

        $this->dispatch('location-updated');
        $this->mount($this->business, $this->isAdminEditing);
    }

    /**
     * Elimina una ubicación del array y de la base de datos.
     */
    public function removeLocation(int $locationId): void
    {
        $location = Location::where('business_id', $this->business->id)->find($locationId);

        if ($location) {
            $location->delete();
        }

        session()->flash('message', __('edit-business.location_delete_success'));

        // Recarga las ubicaciones desde la BD
        $this->mount($this->business, $this->isAdminEditing);
        $this->prepareNewLocation();
    }

    public function render()
    {
        $layout = $this->isAdminEditing ? 'admin.layout' : 'layouts.app-content';

        return view('livewire.edit-business-location', [
            'user' => $this->business->user,
            'business' => $this->business,
            'isAdminEditing' => $this->isAdminEditing
        ]);
    }
}
