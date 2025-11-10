@php
    // Si es un admin editando, usa el layout de admin.
    // Si no, usa el layout de contenido de la app.
    $layout = $isAdminEditing ? 'admin.layout' : 'layouts.app-content';
@endphp
<x-dynamic-component :component="$layout" :user="$user" :business="$business" :isAdminEditing="$isAdminEditing">

<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="w-full max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('edit-business.sms_links_title') }}</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ __('edit-business.sms_links_subtitle') }}
        </p>

        @if (session()->has('message'))
            <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <!-- Formulario para añadir/editar enlaces -->
        <form wire:submit.prevent="save" class="mt-8 space-y-6 border-b dark:border-zinc-700 pb-8 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{ $editingId ? __('edit-business.edit_link_heading') : __('edit-business.add_link_heading') }}
            </h3>

            <!-- Número de teléfono -->
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.sms_url_label') }}</label>
                <input wire:model.lazy="url" type="tel" id="url" required placeholder="{{ __('edit-business.sms_url_placeholder') }}"
                       class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Alias -->
            <div>
                <label for="alias" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.sms_alias_label') }}</label>
                <input wire:model.lazy="alias" type="text" id="alias" placeholder="{{ __('edit-business.sms_alias_placeholder') }}"
                       class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('alias') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Visibilidad -->
            <div class="flex items-center">
                <input wire:model="is_public" type="checkbox" id="is_public" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-zinc-700 dark:border-zinc-600">
                <label for="is_public" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">{{ __('edit-business.social_link_public') }}</label>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                @if($editingId)
                    <button type="button" wire:click="resetForm" class="px-6 py-3 font-semibold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-zinc-600 dark:text-gray-200 dark:hover:bg-zinc-500">
                        {{ __('edit-business.cancel_button') }}
                    </button>
                @endif
                <button type="submit" class="px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ $editingId ? __('edit-business.update_link_button') : __('edit-business.add_sms_link_button') }}
                    <div wire:loading wire:target="save" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"></div>
                </button>
            </div>
        </form>

        <!-- Lista de enlaces existentes con drag & drop -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('edit-business.sms_links_title') }}</h3>
            <div x-data="{ dragging: null, drop(e, id) { if (this.dragging === id) return; let ids = Array.from($refs.smsList.children).map(el => el.dataset.id); let from = ids.indexOf(this.dragging.toString()); let to = ids.indexOf(id.toString()); ids.splice(to, 0, ids.splice(from, 1)[0]); $wire.reorder(ids); this.dragging = null; } }">
                <div x-ref="smsList">
                    @foreach($links as $link)
                        @include('partials.sms-link-item', ['link' => $link])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</x-dynamic-component>