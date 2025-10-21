<x-admin.layout :user="$user" :business="$user->business" :isAdminEditing="true">
    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="w-full max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.edit_subscription_title') }}</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">
                {{ __('admin.edit_subscription_subtitle', ['user' => $user->name]) }}
            </p>

            @if (session()->has('message'))
                <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="mt-8 space-y-6">
                <!-- Plan -->
                <div>
                    <label for="plan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.table_header_plan') }}</label>
                    <select wire:model.live="plan_id" id="plan_id" class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                        <option value="">{{ __('admin.select_plan') }}</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan['id'] }}">{{ $plan['name'] }}</option>
                        @endforeach
                    </select>
                    @error('plan_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha de Inicio -->
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.table_header_start_date') }}</label>
                    <input wire:model.lazy="starts_at" type="date" id="starts_at" required
                           class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                    @error('starts_at') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha de Fin -->
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.table_header_end_date') }}</label>
                    @php
                        // El plan personalizado tendrá 'months' == 0.
                        $selectedPlan = $plans->firstWhere('id', $plan_id);
                        $isCustomPlan = $selectedPlan && $selectedPlan->months === 0;
                    @endphp
                    <input wire:model.lazy="ends_at" type="date" id="ends_at"
                           class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600 {{ !$isCustomPlan ? 'cursor-not-allowed bg-gray-200 dark:bg-zinc-800' : '' }}" {{ !$isCustomPlan ? 'readonly' : '' }}>
                    @error('ends_at') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('admin.save_button') }}
                        <div wire:loading wire:target="save" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"></div>
                    </button>
                </div>
            </form>
        </div>

        {{-- Historial de Suscripciones --}}
        @if($subscriptionHistory->count() > 0)
            <div class="mt-12 w-full max-w-4xl mx-auto">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('admin.subscription_history_title') }}</h3>
                <div class="mt-4 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-zinc-700">
                                <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">{{ __('admin.table_header_plan') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">{{ __('admin.table_header_start_date') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">{{ __('admin.table_header_end_date') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">{{ __('admin.table_header_status') }}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                                @foreach($subscriptionHistory as $subscription)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-0">{{ $subscription->plan->name ?? 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $subscription->starts_at->format('d/m/Y') }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            @php
                                                $status = 'expired';
                                                if (!$subscription->ends_at || $subscription->ends_at->isFuture()) {
                                                    $status = 'active';
                                                }
                                                $statusClass = match($status) {
                                                    'active' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                    'expired' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ __('admin.status_' . $status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin.layout>