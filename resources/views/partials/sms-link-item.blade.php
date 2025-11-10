@props(['link'])

<div
    x-data="{ isDragging: false }"
    @dragstart="isDragging = true; $event.dataTransfer.setData('text/plain', {{ $link->id }}); $root.dragging = {{ $link->id }};"
    @dragend="isDragging = false; $root.dragging = null;"
    @dragover.prevent="$event.dataTransfer.dropEffect = 'move'"
    @dragleave.prevent
    @drop.prevent="$root.drop($event, {{ $link->id }})"
    draggable="true"
    data-id="{{ $link->id }}"
    wire:key="link-{{ $link->id }}"
    :class="{ 'opacity-50': isDragging }"
    class="flex items-center justify-between p-4 bg-white dark:bg-zinc-800/50 border border-neutral-200 dark:border-zinc-700 rounded-lg shadow-sm"
>
    <div class="flex items-center gap-4">
        <div class="cursor-grab" wire:sortable.handle>
            <x-icon name="bars-3" class="h-5 w-5 text-gray-400" />
        </div>
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                <x-icon name="chat-bubble-left-right" class="h-6 w-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-gray-200">
                    {{ $link->alias ?: str_replace('sms:', '', $link->url) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ str_replace('sms:', '', $link->url) }}
                </p>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button wire:click="edit({{ $link->id }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            <x-icon name="pencil" class="h-5 w-5" />
        </button>
        <button wire:click="delete({{ $link->id }})" wire:confirm="{{ __('admin.confirm_delete_button') }}" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-red-500">
            <x-icon name="trash" class="h-5 w-5" />
        </button>
    </div>
</div>