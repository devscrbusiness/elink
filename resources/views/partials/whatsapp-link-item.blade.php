<div
    class="flex items-center justify-between p-4 mb-2 bg-gray-50 dark:bg-zinc-900/50 rounded-lg cursor-move"
    draggable="true"
    wire:key="link-{{ $link->id }}"
    data-id="{{ $link->id }}"
    @dragstart="dragging = {{ $link->id }}"
    @dragend="dragging = null"
    @dragover.prevent
    @drop.prevent="drop($event, {{ $link->id }})"
    :class="{ 'ring-2 ring-pink-400': dragging === {{ $link->id }} }"
>
    <div class="flex items-center space-x-4">
        <x-icon name="arrows-up-down" class="w-5 h-5 text-gray-400"/>
        <x-icons.social.whatsapp class="w-6 h-6 text-gray-500 dark:text-gray-400" />
        <div>
            <p class="font-semibold text-gray-800 dark:text-white">{{ $link->alias ?? $link->url }}</p>
            <a href="{{ $link->url }}" target="_blank" class="text-sm text-blue-500 hover:underline break-all">{{ $link->url }}</a>
        </div>
    </div>
    <div class="flex space-x-2">
        <button wire:click="edit({{ $link->id }})" class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400"><x-icon name="pencil" class="w-5 h-5"/></button>
        <button wire:click="delete({{ $link->id }})" wire:confirm="¿Estás seguro de que quieres eliminar este enlace?" class="text-gray-500 hover:text-red-600 dark:hover:text-red-400"><x-icon name="trash" class="w-5 h-5"/></button>
    </div>
</div>