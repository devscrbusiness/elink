<div class="flex flex-col items-center justify-center h-full p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="w-full max-w-md text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('create-business.welcome_title') }}</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ __('create-business.welcome_subtitle') }}
        </p>

        <form wire:submit.prevent="save" class="mt-8 space-y-6">
            <div>
                <label for="name" class="sr-only">{{ __('create-business.business_name_label') }}</label>
                <input wire:model.lazy="name" type="text" id="name" placeholder="{{ __('create-business.business_name_placeholder') }}" required
                       class="w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="custom_link" class="sr-only">{{ __('create-business.custom_link_label') }}</label>
                <div class="flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-zinc-600 dark:text-gray-300 dark:border-zinc-600">elink/</span>
                    <input wire:model.lazy="custom_link" type="text" id="custom_link" placeholder="{{ __('create-business.custom_link_placeholder') }}" required
                           class="flex-1 w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                </div>
                @error('custom_link') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full px-4 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('create-business.create_business_button') }}
            </button>
        </form>
    </div>
</div>