@php
    $layout = $isAdminEditing ? 'admin.layout' : 'layouts.app-content';
@endphp
<x-dynamic-component :component="$layout" :user="$user" :business="$business" :isAdminEditing="$isAdminEditing">

    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="w-full max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('edit-business.documents_title') }}</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ __('edit-business.documents_subtitle') }}</p>

            @if (session()->has('message'))
                <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Formulario para añadir nuevo documento --}}
            <form wire:submit.prevent="saveDocument" enctype="multipart/form-data" class="mt-8 p-6 border border-dashed border-gray-300 dark:border-zinc-600 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="documentName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.document_name_label') }}</label>
                        <input wire:model="documentName" type="text" id="documentName" class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                        @error('documentName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="documentFile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.document_file_label') }}</label>
                        <input wire:model="documentFile" type="file" id="documentFile" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-zinc-700 dark:file:text-gray-200 dark:hover:file:bg-zinc-600">
                        @error('documentFile') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <button type="submit" class="px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('edit-business.add_document_button') }}
                        <div wire:loading wire:target="saveDocument" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"></div>
                    </button>
                </div>
            </form>

            {{-- Lista de documentos existentes --}}
            <div class="mt-10">
                @forelse($documents as $document)
                    @if($editingDocumentId === $document->id)
                        <div class="p-4 border-b border-gray-200 dark:border-zinc-700">
                            <div class="flex items-center gap-3">
                                <x-icon name="document-text" class="w-6 h-6 text-gray-400" />
                                <input wire:model="editingDocumentName" wire:keydown.enter="updateDocument" wire:keydown.escape="cancelEdit" type="text" class="flex-1 px-2 py-1 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                                <button wire:click="updateDocument" class="p-2 text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors" title="{{ __('edit-business.save_button') }}">
                                    <x-icon name="check" class="w-5 h-5" />
                                </button>
                                <button wire:click="cancelEdit" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="{{ __('edit-business.cancel_button') }}">
                                    <x-icon name="x-mark" class="w-5 h-5" />
                                </button>
                            </div>
                            @error('editingDocumentName') <span class="text-red-500 text-sm mt-1 ml-9">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zinc-700 group">
                            <div class="flex items-center gap-3">
                                <x-icon name="document-text" class="w-6 h-6 text-gray-400" />
                                <a href="{{ asset('storage/' . $document->path) }}" target="_blank" class="font-medium text-gray-800 dark:text-gray-200 hover:underline">{{ $document->name }}</a>
                            </div>
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="editDocument({{ $document->id }})" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="{{ __('edit-business.edit_button') }}"><x-icon name="pencil" class="w-5 h-5" /></button>
                                <button wire:click="deleteDocument({{ $document->id }})" wire:confirm="{{ __('admin.delete_user_confirmation') }}" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="{{ __('edit-business.delete_button') }}"><x-icon name="trash" class="w-5 h-5" /></button>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        {{ __('edit-business.no_documents') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</x-dynamic-component>