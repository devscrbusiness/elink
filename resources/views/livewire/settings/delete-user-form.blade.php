<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('settings.delete_account_title') }}</flux:heading>
        <flux:subheading>{{ __('settings.delete_account_subtitle') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('settings.delete_account_button') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('settings.delete_account_confirmation_title') }}</flux:heading>

                <flux:subheading>
                    {{ __('settings.delete_account_confirmation_text') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('settings.password_nav')" type="password" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('app.cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('settings.delete_account_button') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
