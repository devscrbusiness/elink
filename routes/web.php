<?php

use App\Livewire\BusinessPublic;
use App\Http\Controllers\RedirectController;
use App\Livewire\EditBusinessData;
use App\Livewire\EditBusinessLocation;
use App\Livewire\EditBusinessSocialLinks;
use App\Livewire\EditBusinessWhatsapp;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas solo para Administradores (rol = 1)
    Route::middleware('role:1')->group(function () {
        Route::get('/admin/dashboard', function () {
            return 'Bienvenido, Administrador';
        })->name('admin.dashboard');
    });

    // Rutas solo para Usuarios (rol = 2)
    Route::middleware('role:2')->group(function () {
        Route::get('/user/profile', function () {
            return 'Este es tu perfil de usuario';
        })->name('user.profile');
    });

    // Rutas para ambos (rol = 1 o 2)
    Route::middleware('role:1,2')->get('/shared-space', function() {
        return 'Este es un espacio compartido para administradores y usuarios.';
    })->name('shared.space');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Rutas para editar la información de la empresa
    Route::get('business/{business}/edit/data', EditBusinessData::class)->name('business.edit.data');
    Route::get('business/{business}/edit/location', EditBusinessLocation::class)->name('business.edit.location');
    Route::get('business/{business}/edit/whatsapp', EditBusinessWhatsapp::class)->name('business.edit.whatsapp');
    Route::get('business/{business}/edit/social-links', EditBusinessSocialLinks::class)->name('business.edit.social-links');
});

// Ruta para la redirección de enlaces personalizados de WhatsApp
Route::get('contact/{slug}', [RedirectController::class, 'whatsapp'])->name('whatsapp.redirect');

require __DIR__.'/auth.php';

Route::get('/{business:custom_link}', BusinessPublic::class)->name('business.public');
