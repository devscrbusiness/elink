@props(['business', 'isAdminEditing'])

<div>
    @if ($isAdminEditing)
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('admin.edit_user_information_title') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('admin.edit_user_information_subtitle') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
    @endif

    <div class="flex items-start max-md:flex-col">
        <div class="me-10 w-full pb-4 md:w-[220px]">
            <flux:navlist>
                <flux:navlist.item :href="route('admin.users.edit.profile', $business->user)" :current="request()->routeIs('admin.users.edit.profile')" wire:navigate>
                    {{ __('admin.user_profile_nav') }}
                </flux:navlist.item>
                <flux:navlist.item :href="route('admin.users.edit.password', $business->user)" :current="request()->routeIs('admin.users.edit.password')" wire:navigate>
                    {{ __('admin.user_password_nav') }}
                </flux:navlist.item>
            </flux:navlist>

            <flux:separator class="my-4" />

            <p class="px-4 pb-2 text-sm font-semibold">{{ __('admin.business_settings_heading') }}</p>
            <flux:navlist>
                <flux:navlist.item :href="route('business.edit.data', $business)" :current="request()->routeIs('business.edit.data')" wire:navigate>
                    {{ __('edit-business.information_nav') }}
                </flux:navlist.item>
                <flux:navlist.item :href="route('business.edit.location', $business)" :current="request()->routeIs('business.edit.location')" wire:navigate>
                    {{ __('edit-business.location_nav') }}
                </flux:navlist.item>
                <flux:navlist.item :href="route('business.edit.whatsapp', $business)" :current="request()->routeIs('business.edit.whatsapp')" wire:navigate>
                    {{ __('edit-business.whatsapp_nav') }}
                </flux:navlist.item>
                <flux:navlist.item :href="route('business.edit.social-links', $business)" :current="request()->routeIs('business.edit.social-links')" wire:navigate>
                    {{ __('edit-business.social_links_nav') }}
                </flux:navlist.item>
            </flux:navlist>
        </div>

        <flux:separator class="md:hidden" />

        <div class="flex-1 self-stretch max-md:pt-6">
            {{ $slot }}
        </div>
    </div>
</div>
