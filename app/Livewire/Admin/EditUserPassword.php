<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class EditUserPassword extends Component
{
    public User $user;

    public string $password = '';
    public string $password_confirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function updateUserPassword(): void
    {
        $validated = $this->validate([
            'password' => ['required', 'string', Password::min(8)],
        ]);

        $this->user->update([
            'password' => Hash::make($validated['password']),
        ]);

        session()->flash('message', __('admin.password_updated_success'));
        $this->reset('password', 'password_confirmation');
    }

    public function render()
    {
        return view('livewire.admin.edit-user-password');
    }
}