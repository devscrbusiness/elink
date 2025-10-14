<div class="flex flex-col gap-6">
    <x-auth-header :title="__('auth.create_an_account')" :description="__('auth.enter_details_to_create')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('auth.name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('auth.full_name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('auth.email_address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
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
                {{ __('auth.create_account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('auth.already_have_an_account') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('auth.log_in') }}</flux:link>
    </div>
</div>
