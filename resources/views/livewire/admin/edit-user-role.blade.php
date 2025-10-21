<x-admin.layout :user="$user" :business="$user->business" :is-admin-editing="true">
    <x-slot name="heading">{{ __('admin.edit_user_role_title') }}</x-slot>
    <x-slot name="subheading">{{ __('admin.edit_user_role_subtitle') }}</x-slot>

    <form wire:submit="save">
        <div class="flex flex-col gap-6">
            <flux:select wire:model="role" :label="__('admin.table_header_role')" :disabled="$user->id === auth()->id()">
                <option value="1">{{ __('admin.role_admin') }}</option>
                <option value="2">{{ __('admin.role_user') }}</option>
            </flux:select>

            @if (session()->has('notification'))
                <div class="rounded-md p-4 {{ session('notification')['type'] === 'success' ? 'bg-green-50 dark:bg-green-900/50' : 'bg-red-50 dark:bg-red-900/50' }}">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-icon name="{{ session('notification')['type'] === 'success' ? 'check-circle' : 'x-circle' }}" class="h-5 w-5 {{ session('notification')['type'] === 'success' ? 'text-green-400' : 'text-red-400' }}" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ session('notification')['type'] === 'success' ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                {{ session('notification')['text'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <flux:button.group class="mt-2">
                <flux:button type="submit" variant="primary">{{ __('admin.save_button') }}</flux:button>
            </flux:button.group>
        </div>
    </form>
</x-admin.layout>