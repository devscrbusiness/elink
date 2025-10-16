@php
    // Si es un admin editando, usa el layout de admin.
    // Si no, usa el layout de contenido de la app.
    $layout = $isAdminEditing ? 'admin.layout' : 'layouts.app-content';
@endphp
<x-dynamic-component :component="$layout" :user="$user" :business="$business" :isAdminEditing="$isAdminEditing">


<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="w-full max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('edit-business.form_title') }}</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ __('edit-business.form_subtitle') }}
        </p>

        @if (session()->has('message'))
            <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-8 space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.business_name_label') }}</label>
                <input wire:model.lazy="name" type="text" id="name" required
                       class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.description_label') }}</label>
                <textarea wire:model.lazy="description" id="description" rows="4"
                          class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600"></textarea>
                @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Website -->
            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.website_label') }}</label>
                <input wire:model.lazy="website" type="url" id="website" placeholder="https://ejemplo.com"
                       class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('website') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Logo -->
            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.logo_label') }}</label>
                <div class="mt-1 flex items-center space-x-4">
                    @if ($logo && method_exists($logo, 'temporaryUrl'))
                        <img src="{{ $logo->temporaryUrl() }}" class="h-16 w-16 rounded-full object-cover">
                    @elseif ($business->logo)
                        <img src="{{ asset('storage/' . $business->logo) }}" class="h-16 w-16 rounded-full object-cover">
                    @else
                        <span class="h-16 w-16 rounded-full bg-gray-200 dark:bg-zinc-700 flex items-center justify-center text-gray-400">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </span>
                    @endif
                    <input wire:model="logo" type="file" id="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-zinc-600 dark:file:text-blue-300 dark:hover:file:bg-zinc-500">
                </div>
                <div wire:loading wire:target="logo" class="mt-2 text-sm text-gray-500">{{ __('edit-business.uploading_logo') }}</div>
                @error('logo') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Custom Link (Read-only) -->
            <!-- Custom Link -->
            <div>
                <label for="custom_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.custom_link_label') }}</label>
                <div class="flex rounded-md shadow-sm mt-1">
                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-zinc-600 dark:text-gray-300 dark:border-zinc-600">elink/</span>
                    <input wire:model.lazy="custom_link" type="text" id="custom_link" required class="flex-1 w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                </div>
                @error('custom_link') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('edit-business.save_button') }}
                    <div wire:loading wire:target="save" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"></div>
                </button>
            </div>
        </form>
    </div>
</x-dynamic-component>
