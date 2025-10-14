<?php

namespace App\Livewire\Admin;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class CreateUser extends Component
{
    public string $name = '';
    public string $email = '';
    public int $role = 2; // Por defecto, rol 'Usuario'
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
            'role' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Crear una empresa asociada al nuevo usuario
        Business::create([
            'user_id' => $user->id,
            'name' => $user->name, // Usar el nombre del usuario como nombre de la empresa por defecto
            'custom_link' => Business::generateUniqueCustomLink($user->name),
            'is_active' => true,
        ]);

        // Usaremos una notificación flash en la sesión para mostrar en la siguiente página.
        session()->flash('notification', ['text' => __('admin.user_created_success'), 'type' => 'success']);

        return $this->redirect(route('admin.users'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.create-user');
    }
}