<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ForgotPassword extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }

    public function render()
    {
        // Obtener usuarios para el carrusel
        $usuarios = User::whereHas('business', fn ($q) => $q->whereNotNull('name')->whereNotNull('logo'))->inRandomOrder()->take(10)->get();
        view()->share('usuarios', $usuarios);

        return view('livewire.auth.forgot-password');
    }
}
