<?php

namespace App\Livewire;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditBusinessData extends Component
{
    use WithFileUploads;

    public Business $business;

    public string $name = '';
    public ?string $description = '';
    public ?string $website = '';
    public $logo; // Puede ser un archivo subido o la ruta existente

    public function mount(Business $business)
    {
        // Asegurarse de que el usuario solo pueda editar su propia empresa
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        $this->business = $business;
        $this->name = $business->name;
        $this->description = $business->description;
        $this->website = $business->website;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
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
        ];

        if ($this->logo && !is_string($this->logo)) {
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