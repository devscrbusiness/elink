<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="w-full max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('edit-business.social_links_title') }}</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ __('edit-business.social_links_subtitle') }}
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

            <!-- Tipo de enlace -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.social_link_type') }}</label>
                <select wire:model="type" id="type" class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                    <option value="telegram">Telegram</option>
                    <option value="instagram">Instagram</option>
                    <option value="facebook">Facebook</option>
                    <option value="x">X (Twitter)</option>
                    <option value="tiktok">TikTok</option>
                    <option value="linkedin">LinkedIn</option>
                    <option value="youtube">YouTube</option>
                    <option value="website">Sitio Web</option>
                    <option value="mail">Mail</option>
                    <option value="other">Otro</option>
                </select>
                @error('type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- URL -->
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.social_link_url') }}</label>
                <input wire:model.lazy="url" type="{{ $type === 'mail' ? 'email' : 'url' }}" id="url" required placeholder="{{ $type === 'mail' ? 'ejemplo@correo.com' : 'https://...' }}"
                       class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Alias -->
            <div>
                <label for="alias" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.social_link_alias') }}</label>                <input wire:model.lazy="alias" type="text" id="alias"
                       class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('alias') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Mensaje de saludo (opcional) -->
            @if (in_array($type, ['whatsapp', 'telegram']))
            <div>
                <label for="greeting" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.social_link_greeting') }}</label>
                <textarea wire:model.lazy="greeting" id="greeting" rows="2"
                          class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600"></textarea>
                @error('greeting') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            @endif

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

        <!-- Lista de enlaces existentes con drag & drop -->
        <div
            x-data="{
                dragging: null,
                start(e, id) { this.dragging = id; },
                end(e) { this.dragging = null; },
                drop(e, id) {
                    if (this.dragging === id) return;
                    let ids = Array.from($refs.list.children).map(el => el.dataset.id);
                    let from = ids.indexOf(this.dragging.toString());
                    let to = ids.indexOf(id.toString());
                    ids.splice(to, 0, ids.splice(from, 1)[0]);
                    $wire.reorder(ids);
                    this.dragging = null;
                }
            }"
        >
            <div x-ref="list">
                @foreach($links as $link)
                    <div
                        class="flex items-center justify-between p-4 mb-2 bg-gray-50 dark:bg-zinc-900/50 rounded-lg cursor-move"
                        draggable="true"
                        data-id="{{ $link->id }}"
                        @dragstart="start($event, {{ $link->id }})"
                        @dragend="end($event)"
                        @dragover.prevent
                        @drop="drop($event, {{ $link->id }})"
                        :class="{ 'ring-2 ring-pink-400': dragging === {{ $link->id }} }"
                    >
                        <div class="flex items-center space-x-4">
                            <x-icon name="arrows-up-down" class="w-5 h-5 text-gray-400"/>
                            <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $link->alias ?? $link->url }}</p>
                                <a href="{{ $link->url }}" target="_blank" class="text-sm text-blue-500 hover:underline">{{ $link->url }}</a>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button wire:click="edit({{ $link->id }})" class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400"><x-icon name="pencil" class="w-5 h-5"/></button>
                            <button wire:click="delete({{ $link->id }})" onclick="return confirm('¿Estás seguro de que quieres eliminar este enlace?')" class="text-gray-500 hover:text-red-600 dark:hover:text-red-400"><x-icon name="trash" class="w-5 h-5"/></button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@alpinejs/sort@latest/dist/cdn.min.js" defer></script>
    @endpush
@endonce