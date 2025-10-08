<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="w-full max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('edit-business.whatsapp_links_title') }}</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ __('edit-business.whatsapp_links_subtitle') }}
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

            <!-- Teléfono -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1">
                    <label for="country_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.whatsapp_country_code') }}</label>
                    <select wire:model="country_id" id="country_id" class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->flag_emoji }} {{ $country->name }} (+{{ $country->phone_code }})</option>
                        @endforeach
                    </select>
                    @error('country_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.whatsapp_phone') }}</label>
                    <input wire:model.lazy="phone_number" type="tel" id="phone_number" required placeholder="991234567"
                           class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                    @error('phone_number') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Alias Personalizado (Slug) -->
            <div>
                <label for="custom_slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.whatsapp_custom_slug') }}</label>
                <div class="flex rounded-md shadow-sm mt-1">
                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-zinc-600 dark:text-gray-300 dark:border-zinc-600">elink.com/contact/</span>
                    <input wire:model.lazy="custom_slug" type="text" id="custom_slug" placeholder="{{ __('edit-business.whatsapp_custom_slug_placeholder') }}"
                           class="flex-1 w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                </div>
                @error('custom_slug') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Alias -->
            <div>
                <label for="alias" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.whatsapp_link_alias') }}</label>
                <input wire:model.lazy="alias" type="text" id="alias"
                       class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('alias') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Mensaje de saludo (opcional) -->
            <div>
                <label for="greeting" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.social_link_greeting') }}</label>
                <textarea wire:model.lazy="greeting" id="greeting" rows="2"
                          class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600"></textarea>
                @error('greeting') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
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
                    {{ $editingId ? __('edit-business.update_link_button') : __('edit-business.add_link_button') }}
                    <div wire:loading wire:target="save" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"></div>
                </button>
            </div>
        </form>

        <!-- Lista de enlaces existentes -->
        <div class="space-y-4">
            @forelse($links as $link)
                <div wire:key="{{ $link->id }}" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <x-icons.social.whatsapp class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $link->alias ?? $link->url }}</p>
                            <a href="{{ $link->url }}" target="_blank" class="text-sm text-blue-500 hover:underline">{{ $link->url }}</a>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button wire:click="edit({{ $link->id }})" class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400"><x-icon name="pencil" class="w-5 h-5"/></button>
                        <button wire:click="delete({{ $link->id }})" wire:confirm="¿Estás seguro de que quieres eliminar este enlace?" class="text-gray-500 hover:text-red-600 dark:hover:text-red-400"><x-icon name="trash" class="w-5 h-5"/></button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-4">{{ __('edit-business.no_whatsapp_links') }}</p>
            @endforelse
        </div>
    </div>
</div>