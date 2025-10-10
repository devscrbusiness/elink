<?php

namespace App\Livewire;

use App\Models\Business;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditBusinessData extends Component
{
    use WithFileUploads;

    public Business $business;

    public bool $isAdminEditing = false;

    public string $name = '';
    public ?string $description = '';
    public ?string $website = '';
    public string $custom_link = '';
    public $logo; // Puede ser un archivo subido o la ruta existente

    public function mount(Business $business)
    {
        $user = Auth::user();
        // Permitir el acceso si el usuario es administrador (role=1) O si es el dueño de la empresa.
        if ($user->role !== 1 && $business->user_id !== $user->id) {
            abort(403, 'No tienes permiso para editar esta empresa.');
        }

        $this->isAdminEditing = ($user->role === 1 && $business->user_id !== $user->id);

        $this->business = $business;
        $this->name = $business->name;
        $this->description = $business->description;
        $this->website = $business->website;
        $this->custom_link = $business->custom_link;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'custom_link' => [
                'required',
                'string',
                'min:3',
                Rule::unique('businesses')->ignore($this->business->id),
            ],
            'logo' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'website' => $this->website,
            'custom_link' => $this->custom_link,
        ];

        if ($this->logo && !is_string($this->logo)) {
            if ($this->business->logo) {
                Storage::disk('public')->delete($this->business->logo);
            }

            $data['logo'] = $this->logo->store('logos', 'public');
        }

        $this->business->update($data);

        session()->flash('message', __('edit-business.update_success'));
    }

    public function render()
    {
        return view('livewire.edit-business-data');
    }
}