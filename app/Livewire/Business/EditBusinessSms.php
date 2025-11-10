<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditBusinessSms extends Component
{
    public Business $business;
    public $links;

    public ?int $editingId = null;
    public string $url = '';
    public ?string $alias = '';
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
        $this->links = $this->business->socialLinks()->where('type', 'sms')->orderBy('position')->get();
    }

    protected function rules(): array
    {
        return [
            // Se valida que el número de teléfono sea numérico y tenga una longitud razonable.
            // El prefijo '+' se manejará antes de la validación.
            'url' => 'required|regex:/^\+?[0-9\s\-]{8,20}$/',
            'alias' => 'nullable|string|max:100',
            'is_public' => 'boolean',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'url' => __('edit-business.sms_url_label'),
            'alias' => __('edit-business.sms_alias_label'),
        ];
    }

    public function save()
    {
        $this->validate();

        // Limpia el número y asegura que tenga el prefijo 'sms:'
        $phoneNumber = 'sms:' . preg_replace('/[^\d+]/', '', $this->url);

        $data = [
            'type' => 'sms',
            'url' => $phoneNumber,
            'alias' => $this->alias,
            'is_public' => $this->is_public,
        ];

        if ($this->editingId) {
            $link = SocialLink::find($this->editingId);
            $link->update($data);
            session()->flash('message', __('edit-business.sms_link_update_success'));
        } else {
            // Asignar la siguiente posición disponible para enlaces de tipo 'sms'
            $maxPosition = $this->business->socialLinks()->where('type', 'sms')->max('position');
            $data['position'] = is_null($maxPosition) ? 0 : $maxPosition + 1;
            $this->business->socialLinks()->create($data);
            session()->flash('message', __('edit-business.sms_link_create_success'));
        }

        $this->resetForm();
        $this->loadLinks();
    }

    public function edit(int $linkId)
    {
        $link = SocialLink::findOrFail($linkId);
        $this->editingId = $link->id;
        // Quita el prefijo 'sms:' para mostrar solo el número en el formulario
        $this->url = str_replace('sms:', '', $link->url);
        $this->alias = $link->alias;
        $this->is_public = $link->is_public;
    }

    public function delete(int $linkId)
    {
        SocialLink::destroy($linkId);
        $this->loadLinks();
        session()->flash('message', __('edit-business.sms_link_delete_success'));
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'url', 'alias', 'is_public']);
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
            SocialLink::where('id', $id)->update(['position' => $index]);
        }
        $this->loadLinks();
    }

    public function render()
    {
        return view('livewire.edit-business-sms', [
            'user' => $this->business->user,
        ]);
    }
}