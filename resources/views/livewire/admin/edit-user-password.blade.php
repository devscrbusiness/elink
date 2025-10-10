<x-admin.layout :business="$user->business" :isAdminEditing="true">
    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.update_password_title') }}</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ __('admin.update_password_subtitle') }}
        </p>

        @if (session()->has('message'))
            <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="updateUserPassword" class="my-6 w-full space-y-6">
            <flux:input wire:model="password" :label="__('app.new_password')" type="password" required />

            <flux:button variant="primary" type="submit">{{ __('app.save') }}</flux:button>
        </form>
    </div>
</x-admin.layout>