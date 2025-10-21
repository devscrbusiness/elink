<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditUserRole extends Component
{
    public User $user;
    public int $role;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->role = $user->role;
    }

    protected function rules(): array
    {
        return [
            'role' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'role' => __('admin.table_header_role'),
        ];
    }

    public function save()
    {
        $this->validate();

        // Evita que un administrador se quite su propio rol de administrador.
        if ($this->user->id === Auth::user()->id() && (int) $this->role !== 1) {
            session()->flash('notification', ['text' => __('admin.cannot_change_own_role'), 'type' => 'error']);
            return;
        }

        $this->user->update([
            'role' => $this->role,
        ]);

        session()->flash('notification', ['text' => __('admin.role_updated_success'), 'type' => 'success']);

        // Recargamos la página para asegurar que el estado se actualice correctamente.
        return $this->redirect(route('admin.users.edit.role', $this->user), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.edit-user-role');
    }
}