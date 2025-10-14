<div class="flex flex-col gap-6">
    <x-auth-header :title="__('auth.reset_password')" :description="__('auth.please_enter_new_password')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('auth.email')"
            type="email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('auth.password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('auth.password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('auth.confirm_password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('auth.confirm_password')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('auth.reset_password') }}
            </flux:button>
        </div>
    </form>
</div>
