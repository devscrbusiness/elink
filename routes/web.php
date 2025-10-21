<?php

use App\Livewire\Admin\EditUserRole;
use App\Livewire\Admin\EditUserPassword;
use App\Livewire\Admin\EditUserSubscription;
use App\Livewire\Admin\EditUserProfile;
use App\Livewire\BusinessPublic;
use App\Livewire\Admin\UserList;
use App\Livewire\Admin\Subscriptions;
use App\Livewire\Business\EditBusinessData;
use App\Livewire\Business\EditBusinessLocation;
use App\Livewire\Business\EditBusinessSocialLinks;
use App\Livewire\Business\EditBusinessWhatsapp;
use App\Http\Controllers\WhatsappRedirectController;
use App\Http\Controllers\LinkRedirectController;
use App\Http\Controllers\LocaleController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::redirect('/', '/login')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas solo para Administradores (rol = 1)
    Route::prefix('admin')->name('admin.')->middleware('role:1')->group(function () {
        Route::get('/dashboard', fn() => 'Bienvenido, Administrador')->name('dashboard');
        Route::get('/users', UserList::class)->name('users');
        Route::get('/users/create', \App\Livewire\Admin\CreateUser::class)->name('users.create');
        Route::get('business/{business}/edit/data', EditBusinessData::class)->name('business.edit.data');
        Route::get('business/{business}/edit/location', EditBusinessLocation::class)->name('business.edit.location');
        Route::get('/subscriptions', Subscriptions::class)->name('subscriptions');


        // Nuevas rutas para editar perfil y contraseña de usuario
        Route::get('users/{user}/edit/profile', EditUserProfile::class)->name('users.edit.profile');
        Route::get('users/{user}/edit/password', EditUserPassword::class)->name('users.edit.password');
        Route::get('users/{user}/edit/role', EditUserRole::class)->name('users.edit.role');
        Route::get('/users/{user}/edit/subscription', EditUserSubscription::class)->name('users.edit.subscription');
        Route::get('business/{business}/edit/whatsapp', EditBusinessWhatsapp::class)->name('business.edit.whatsapp');
        Route::get('business/{business}/edit/social-links', EditBusinessSocialLinks::class)->name('business.edit.social-links');
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

Route::get('locale/{lang}', [LocaleController::class, 'switch'])->name('locale.switch');

// Rutas de redirección para enlaces con tracking (públicas)
Route::get('contact/{slug}', [WhatsappRedirectController::class, 'redirect'])->name('whatsapp.redirect');
Route::get('/wa-link/{id}', [WhatsappRedirectController::class, 'redirectById'])->name('whatsapp.redirect.id');
Route::get('/link/{id}', [LinkRedirectController::class, 'redirect'])->name('link.redirect');

require __DIR__.'/auth.php';

Route::get('/{business:custom_link}', BusinessPublic::class)->name('business.public');
