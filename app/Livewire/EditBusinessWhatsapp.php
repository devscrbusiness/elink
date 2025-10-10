<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\Business;
use App\Models\WhatsappLink;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditBusinessWhatsapp extends Component
{
    public Business $business;
    public $links;
    public $countries;

    public ?int $editingId = null;
    public ?string $country_id = '1'; // Ecuador por defecto (ID 1 en el seeder)
    public string $phone_number = '';
    public ?string $custom_slug = null;
    public ?string $alias = '';
    public ?string $greeting = null;
    public bool $is_public = true;
    public bool $isAdminEditing = false;

    public function mount(Business $business)
    {
        $user = Auth::user();
        // Permitir el acceso si el usuario es administrador (role=1) O si es el dueño de la empresa.
        if ($user->role !== 1 && $business->user_id !== $user->id) {
            abort(403, 'No tienes permiso para editar esta empresa.');
        }

        $this->isAdminEditing = ($user->role === 1 && $business->user_id !== $user->id);

        $this->business = $business;
        $this->countries = Country::orderBy('name')->get();
        $this->loadLinks();
    }

    public function loadLinks()
    {
        $this->links = $this->business->whatsappLinks()->orderBy('position')->get();
    }

    protected function rules(): array
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'phone_number' => 'required|numeric|digits_between:8,12',
            'custom_slug' => [
                'nullable',
                'string',
                'alpha_dash',
                Rule::unique('whatsapp_links', 'custom_slug')->ignore($this->editingId),
            ],
            'alias' => 'nullable|string|max:100',
            'greeting' => 'nullable|string|max:255',
            'is_public' => 'boolean',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'country_id' => __('edit-business.whatsapp_country_code'),
            'phone_number' => __('edit-business.whatsapp_phone'),
            'custom_slug' => __('edit-business.whatsapp_custom_slug'),
            'alias' => __('edit-business.social_link_alias'),
            'greeting' => __('edit-business.social_link_greeting'),
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'country_id' => $this->country_id,
            'phone_number' => $this->phone_number,
            'custom_slug' => $this->custom_slug,
            'alias' => $this->alias,
            'greeting' => $this->greeting,
            'is_public' => $this->is_public,
        ];

        if ($this->editingId) {
            $link = WhatsappLink::find($this->editingId);
            $link->update($data);
            session()->flash('message', __('edit-business.social_link_update_success'));
        } else {
            // Asignar la siguiente posición disponible
            $maxPosition = $this->business->whatsappLinks()->max('position');
            $data['position'] = is_null($maxPosition) ? 0 : $maxPosition + 1;
            $this->business->whatsappLinks()->create($data);
            session()->flash('message', __('edit-business.social_link_create_success'));
        }

        $this->resetForm();
        $this->loadLinks();
    }

    public function edit(int $linkId)
    {
        $link = WhatsappLink::findOrFail($linkId);
        $this->editingId = $link->id;
        $this->country_id = $link->country_id;
        $this->phone_number = $link->phone_number;
        $this->custom_slug = $link->custom_slug;
        $this->alias = $link->alias;
        $this->greeting = $link->greeting;
        $this->is_public = $link->is_public;
    }

    public function delete(int $linkId)
    {
        WhatsappLink::destroy($linkId);
        $this->loadLinks();
        session()->flash('message', __('edit-business.social_link_delete_success'));
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'phone_number', 'custom_slug', 'alias', 'greeting', 'is_public']);
        $this->country_id = '1'; // Ecuador por defecto
        $this->is_public = true;
    }

    /**
     * Actualiza el orden de los enlaces cuando el usuario los arrastra.
     *
     * @param  array  $orderedIds
     * @return void
     */
    public function reorder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            WhatsappLink::where('id', $id)->update(['position' => $index]);
        }
        $this->loadLinks();
    }

    public function render()
    {
        return view('livewire.edit-business-whatsapp', [
            'countries' => $this->countries,
        ]);
    }
}