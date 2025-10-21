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
                    <input wire:model.lazy="ends_at" type="date" id="ends_at"
                           class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
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
    </div>
</x-admin.layout>