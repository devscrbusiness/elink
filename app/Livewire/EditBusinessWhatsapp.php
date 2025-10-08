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

    public function mount(Business $business)
    {
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        $this->business = $business;
        $this->countries = Country::orderBy('name')->get();
        $this->loadLinks();
    }

    public function loadLinks()
    {
        $this->links = $this->business->whatsappLinks()->get();
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
        $this->reset(['editingId', 'country_id', 'phone_number', 'custom_slug', 'alias', 'greeting', 'is_public']);
        $this->is_public = true;
    }

    public function render()
    {
        return view('livewire.edit-business-whatsapp', [
            'countries' => $this->countries,
        ]);
    }
}