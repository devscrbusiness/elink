<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.subscriptions_title') }}</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ __('admin.subscriptions_subtitle') }}</p>
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-grow">
            <input type="text" wire:model.live="query" placeholder="{{ __('admin.search_placeholder') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-gray-400 dark:text-white">
        </div>
        <div class="flex-shrink-0 flex items-center gap-2">
            <button wire:click="setStatusFilter('all')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $statusFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600' }}">
                {{ __('admin.filter_all') }}
            </button>
            <button wire:click="setStatusFilter('active')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $statusFilter === 'active' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600' }}">
                {{ __('admin.filter_active') }}
            </button>
            <button wire:click="setStatusFilter('expired')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $statusFilter === 'expired' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600' }}">
                {{ __('admin.filter_expired') }}
            </button>
        </div>
    </div>

    <div class="mt-8">
        <!-- Encabezados -->
        <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-2 rounded-full text-sm font-bold bg-blue-600 text-white uppercase tracking-wider">
            <div class="col-span-3">{{ __('admin.table_header_user') }}</div>
            <div class="col-span-2">{{ __('admin.table_header_plan') }}</div>
            <div class="col-span-2 text-center">{{ __('admin.table_header_status') }}</div>
            <div class="col-span-2">{{ __('admin.table_header_start_date') }}</div>
            <div class="col-span-2">{{ __('admin.table_header_end_date') }}</div>
            <div class="col-span-1 text-right">{{ __('admin.table_header_actions') }}</div>
        </div>

        <!-- Lista de suscripciones -->
        <div class="space-y-2 mt-4">
            @forelse ($users as $user)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center p-4 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-zinc-900/50 dark:hover:bg-zinc-700/50 transition-colors">
                    <!-- Usuario -->
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
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->business->name ?? '' }}</div>
                        </div>
                    </div>

                    @if ($user->latestSubscription)
                        <!-- Plan -->
                        <div class="md:col-span-2 text-sm text-gray-500 dark:text-gray-300"><span class="md:hidden font-semibold text-gray-600 dark:text-gray-400">{{ __('admin.table_header_plan') }}: </span>{{ $user->latestSubscription->plan->name ?? 'N/A' }}</div>
                        
                        <!-- Estado -->
                        <div class="md:col-span-2 text-left md:text-center">
                            @php
                                $statusClass = match($user->latestSubscription->status) {
                                    'active' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                    'cancelled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                    'expired' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300',
                                };
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ __('admin.status_' . $user->latestSubscription->status) }}
                            </span>
                        </div>

                        <!-- Fecha de Inicio -->
                        <div class="md:col-span-2 text-sm text-gray-500 dark:text-gray-300"><span class="md:hidden font-semibold text-gray-600 dark:text-gray-400">{{ __('admin.table_header_start_date') }}: </span>{{ $user->latestSubscription->starts_at->format('d/m/Y') }}</div>

                        <!-- Fecha de Fin -->
                        <div class="md:col-span-2 text-sm text-gray-500 dark:text-gray-300"><span class="md:hidden font-semibold text-gray-600 dark:text-gray-400">{{ __('admin.table_header_end_date') }}: </span>{{ $user->latestSubscription->ends_at ? $user->latestSubscription->ends_at->format('d/m/Y') : 'N/A' }}</div>
                    @else
                        <div class="md:col-span-8 text-sm text-center text-gray-400 dark:text-gray-500 italic">
                            {{ __('admin.no_subscriptions_found') }}
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="md:col-span-1 flex items-center justify-start md:justify-end space-x-2">
                        <a href="{{ route('admin.users.edit.subscription', $user) }}" wire:navigate class="p-2 text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 transition-colors" title="{{ __('admin.edit_subscription_tooltip') }}">
                            <x-icon name="pencil" class="w-5 h-5" />
                        </a>
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