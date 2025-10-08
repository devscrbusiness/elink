<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditBusinessSocialLinks extends Component
{
    public Business $business;
    public $links;

    public ?int $editingId = null;
    public string $type = 'website';
    public string $url = '';
    public string $alias = '';
    public ?string $greeting = null;
    public bool $is_public = true;

    public function mount(Business $business)
    {
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        $this->business = $business;
        $this->loadLinks();
    }

    public function loadLinks()
    {
        $this->links = $this->business->socialLinks()
            ->where('type', '!=', 'whatsapp')
            ->orderBy('position')->get();
    }

    protected function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['telegram', 'instagram', 'facebook', 'x', 'tiktok', 'linkedin', 'youtube', 'website', 'mail', 'other'])],
            'url' => [
                'required',
                'max:255',
                // Si el tipo es 'mail', valida como email, si no, como URL.
                $this->type === 'mail' ? 'email' : 'url',
            ],
            'alias' => 'nullable|string|max:100',
            'greeting' => 'nullable|string|max:255',
            'is_public' => 'boolean',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'type' => __('edit-business.social_link_type'),
            'url' => __('edit-business.social_link_url'),
            'alias' => __('edit-business.social_link_alias'),
            'greeting' => __('edit-business.social_link_greeting'),
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'type' => $this->type,
            'url' => $this->type === 'mail' ? 'mailto:'.$this->url : $this->url,
            'alias' => $this->alias,
            'greeting' => $this->greeting,
            'is_public' => $this->is_public,
        ];

        if ($this->editingId) {
            $link = SocialLink::find($this->editingId);
            $link->update($data);
            session()->flash('message', __('edit-business.social_link_update_success'));
        } else {
            // Asignar la siguiente posición disponible
            $maxPosition = $this->business->socialLinks()->max('position') ?? 0;
            $data['position'] = $maxPosition + 1;
            $this->business->socialLinks()->create($data);
            session()->flash('message', __('edit-business.social_link_create_success'));
        }

        $this->resetForm();
        $this->loadLinks();
    }

    public function edit(int $linkId)
    {
        $link = SocialLink::findOrFail($linkId);
        $this->editingId = $link->id;
        $this->type = $link->type;
        $this->url = $this->type === 'mail' ? str_replace('mailto:', '', $link->url) : $link->url;
        $this->alias = $link->alias;
        $this->greeting = $link->greeting;
        $this->is_public = $link->is_public;
    }

    public function delete(int $linkId)
    {
        SocialLink::destroy($linkId);
        $this->loadLinks();
        session()->flash('message', __('edit-business.social_link_delete_success'));
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'type', 'url', 'alias', 'greeting', 'is_public']);
        $this->type = 'website';
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
            $link = $this->links->find($id);
            if ($link) {
                $link->update(['position' => $index]);
            }
        }
        
        $this->links = $this->links->sortBy('position')->values();
    }

    public function render()
    {
        return view('livewire.edit-business-social-links');
    }
}