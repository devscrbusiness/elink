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
    public $socialNetworks;
    public $mails;
    public $websites;

    public ?int $editingId = null;
    public string $type = 'website';
    public string $url = '';
    public string $alias = '';
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
        $this->loadLinks();
    }

    public function loadLinks()
    {
        $allLinks = $this->business->socialLinks()
            ->where('type', '!=', 'whatsapp')
            ->orderBy('position')->get();

        $socialNetworkTypes = ['telegram', 'instagram', 'facebook', 'x', 'tiktok', 'linkedin', 'youtube', 'other'];

        $this->socialNetworks = $allLinks->whereIn('type', $socialNetworkTypes)->values();
        $this->mails = $allLinks->where('type', 'mail')->values();
        $this->websites = $allLinks->where('type', 'website')->values();
    }

    protected function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                // Se incluyen todos los tipos de enlaces sociales, de correo y sitios web.
                Rule::in(['telegram', 'instagram', 'facebook', 'x', 'tiktok', 'linkedin', 'youtube', 'website', 'mail', 'other'])
            ],
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
            $socialNetworkTypes = ['telegram', 'instagram', 'facebook', 'x', 'tiktok', 'linkedin', 'youtube', 'other'];
            
            if (in_array($this->type, $socialNetworkTypes)) {
                $maxPosition = $this->business->socialLinks()->whereIn('type', $socialNetworkTypes)->max('position') ?? -1;
            } elseif ($this->type === 'mail') {
                $maxPosition = $this->business->socialLinks()->where('type', 'mail')->max('position') ?? -1;
            } elseif ($this->type === 'website') {
                $maxPosition = $this->business->socialLinks()->where('type', 'website')->max('position') ?? -1;
            } else {
                $maxPosition = $this->business->socialLinks()->max('position') ?? -1;
            }

            // Asignar la siguiente posición disponible
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
     * Actualiza el orden de las redes sociales.
     *
     * @param  array  $orderedIds
     * @return void
     */
    public function reorderSocials($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            SocialLink::where('id', $id)->update(['position' => $index]);
        }
        $this->loadLinks();
    }

    /**
     * Actualiza el orden de los correos.
     *
     * @param  array  $orderedIds
     * @return void
     */
    public function reorderMails($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            SocialLink::where('id', $id)->update(['position' => $index]);
        }
        $this->loadLinks();
    }

    /**
     * Actualiza el orden de los sitios web.
     *
     * @param  array  $orderedIds
     * @return void
     */
    public function reorderWebsites($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            SocialLink::where('id', $id)->update(['position' => $index]);
        }
        $this->loadLinks();
    }

    public function render()
    {
        return view('livewire.edit-business-social-links');
    }
}