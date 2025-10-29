<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    {{-- Saludo y Acciones Rápidas --}}
    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.dashboard_title') }}</h2>
        <p class="mt-1 text-gray-600 dark:text-gray-300">{{ __('admin.dashboard_welcome') }}</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.users') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
                <x-icon name="users" class="w-4 h-4" />
                {{ __('admin.users_navigation') }}
            </a>
            <a href="{{ route('admin.subscriptions') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600">
                <x-icon name="credit-card" class="w-4 h-4" />
                {{ __('admin.subscriptions_navigation') }}
            </a>
        </div>
    </div>

    {{-- Estadísticas Generales --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700 text-center">
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalUsers }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ trans_choice('admin.user_plural', $totalUsers) }}</p>
        </div>
        <div class="p-4 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700 text-center">
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalBusinesses }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ trans_choice('admin.business_plural', $totalBusinesses) }}</p>
        </div>
        <div class="p-4 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700 text-center">
            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $activeSubscriptions }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ trans_choice('admin.active_subscriptions_plural', $activeSubscriptions) }}</p>
        </div>
        <div class="p-4 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700 text-center">
            <p class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $totalLinks }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ trans_choice('admin.link_plural', $totalLinks) }}</p>
        </div>
    </div>

    {{-- Últimos Usuarios Registrados --}}
    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('admin.recent_users_title') }}</h3>
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-zinc-700">
                        <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">Usuario</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">{{ __('admin.table_header_business') }}</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">{{ __('admin.table_header_registration_date') }}</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                        @forelse($recentUsers as $user)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-zinc-700 text-sm font-bold text-gray-500 dark:text-gray-300">
                                                {{ $user->initials() }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->business->name ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->created_at->translatedFormat('d M, Y') }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="{{ route('admin.users.edit.profile', $user) }}" wire:navigate class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Editar<span class="sr-only">, {{ $user->name }}</span></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-gray-500">No hay usuarios recientes.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas por Empresa --}}
    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('admin.business_stats_title') }}</h3>
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-zinc-700">
                        <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">{{ __('admin.table_header_business') }}</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">{{ __('admin.table_header_total_visits') }}</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">{{ __('admin.table_header_total_clicks') }}</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Ver Detalles</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                        @forelse($businessesWithStats as $business)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                        @if ($business->logo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $business->logo) }}" alt="Logo de {{ $business->name }}">
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-zinc-700 text-sm font-bold text-gray-500 dark:text-gray-300">
                                                {{ substr($business->name, 0, 2) }}
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $business->name }}</div>
                                            <div class="text-gray-500 dark:text-gray-400">{{ $business->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $business->visits_count }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $business->total_clicks }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    {{-- Link to view detailed stats for this business --}}
                                    <a href="{{ route('business.edit.data', $business) }}" wire:navigate class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ __('admin.view_public_profile_tooltip') }}<span class="sr-only">, {{ $business->name }}</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-gray-500">{{ __('admin.no_businesses_found') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
