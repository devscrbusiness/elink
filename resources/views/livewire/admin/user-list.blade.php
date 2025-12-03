<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.users_title') }}</h2>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
            <x-icon name="plus" class="w-4 h-4" />
            <span>{{ __('admin.add_user_button') }}</span>
        </a>
    </div>

    <input type="text" wire:model.live="query" placeholder="{{ __('admin.search_placeholder') }}" class="mt-4 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-gray-400 dark:text-white">

    <div class="mt-8">
        <!-- Encabezados -->
        <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-2 rounded-full text-sm font-bold bg-blue-600 text-white uppercase tracking-wider">
            <div class="col-span-3">{{ __('admin.table_header_name') }}</div>
            <div class="col-span-3">{{ __('admin.table_header_email') }}</div>
            <div class="col-span-1 text-center">{{ __('admin.table_header_role') }}</div>
            <div class="col-span-1 text-center">{{ __('admin.table_header_favorite') }}</div>
            <div class="col-span-2 text-center">{{ __('admin.table_header_business') }}</div>
            <div class="col-span-2 text-right">{{ __('admin.table_header_actions') }}</div>
        </div>

        <!-- Lista de usuarios -->
        <div class="space-y-2 mt-4">
            @forelse ($users as $user)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center p-4 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-zinc-900/50 dark:hover:bg-zinc-700/50 transition-colors border-b border-gray-200 dark:border-zinc-700">
                    <!-- Nombre y Avatar -->
                    <div class="md:col-span-3 flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if ($user->business && $user->business->logo)
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $user->business->logo) }}" alt="Logo de {{ $user->business->name }}">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-zinc-700 text-sm font-bold text-gray-500 dark:text-gray-300">
                                    {{ $user->initials() }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4"> {{-- Este div contiene el nombre y email --}}
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                            <div class="md:hidden text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                        </div>
                    </div>

                    <!-- Email (oculto en móvil) -->
                    <div class="hidden md:block md:col-span-3 text-sm text-gray-500 dark:text-gray-300 truncate">{{ $user->email }}</div>

                    <!-- Rol -->
                    <div class="md:col-span-1 text-left md:text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 1 ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' }}">
                            {{ $user->role === 1 ? __('admin.role_admin') : __('admin.role_user') }}
                        </span>
                    </div>

                    {{-- Switch de Favorito --}}
                    <div class="md:col-span-1 text-center">
                        <flux:switch wire:click="toggleFavorite({{ $user->id }})" :checked="$user->is_favorite" id="favorite-switch-{{ $user->id }}" />
                    </div>

                    <!-- Empresa -->
                    <div class="md:col-span-2 text-left md:text-center text-sm text-gray-500 dark:text-gray-300 truncate"><span class="md:hidden font-semibold text-gray-600 dark:text-gray-400">{{ __('admin.business_label') }} </span>{{ $user->business->name ?? 'N/A' }}</div>

                    <!-- Acciones -->
                    <div class="md:col-span-2 flex items-center justify-start md:justify-end space-x-2"> {{-- Ajustado a col-span-2 --}}
                        @if($user->business)
                        <a href="{{ route('business.public', $user->business->custom_link) }}" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="{{ __('admin.view_public_profile_tooltip') }}">
                            <x-icon name="eye" class="w-5 h-5" />
                        </a>
                        @endif
                        {{-- El enlace de edición cambia según si el usuario tiene una empresa o no --}}
                        @if (auth()->id() !== $user->id)
                            @if($user->business)
                                <a href="{{ route('admin.business.edit.data', $user->business) }}" wire:navigate class="p-2 text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 transition-colors" title="{{ __('admin.edit_user_tooltip') }}">
                                    <x-icon name="pencil" class="w-5 h-5" />
                                </a>
                            @else
                                <a href="{{ route('admin.users.edit.profile', $user) }}" wire:navigate class="p-2 text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 transition-colors" title="{{ __('admin.edit_user_tooltip') }}">
                                    <x-icon name="pencil" class="w-5 h-5" />
                                </a>
                            @endif
                        
                        <flux:modal.trigger name="delete-profile-{{ $user->id }}">
                            <button class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors" title="{{ __('admin.delete_user_tooltip') }}">
                                    <x-icon name="trash" class="w-5 h-5" />
                            </button>
                        </flux:modal.trigger>
                        <flux:modal name="delete-profile-{{ $user->id }}" class="min-w-[22rem]">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">{{ __('admin.delete_user_title') }}</flux:heading>
                                    <flux:text class="mt-2">
                                        <p>{{ __('admin.delete_user_confirmation') }}</p>
                                    </flux:text>
                                </div>
                                <div class="flex gap-2">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="ghost">{{ __('admin.cancel_button') }}</flux:button>
                                    </flux:modal.close>
                                    <flux:button variant="danger" wire:click="deleteUser({{ $user->id }})">{{ __('admin.confirm_delete_button') }}</flux:button>
                                </div>
                            </div>
                        </flux:modal>

                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    {{ __('admin.no_users_found') }}
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>