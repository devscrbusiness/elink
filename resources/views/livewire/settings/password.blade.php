<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('settings.update_password_title')" :subheading="__('settings.update_password_subtitle')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('settings.current_password')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="__('settings.new_password')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('settings.confirm_password')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('app.save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('settings.saved') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
