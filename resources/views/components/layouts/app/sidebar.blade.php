<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('app.platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('app.dashboard') }}</flux:navlist.item>
                </flux:navlist.group>

                @if (auth()->user()->role === 1)
                    <flux:navlist.group :heading="__('admin.navigation_heading')" class="grid">
                        @php
                            $isEditingOtherUserBusiness = request()->routeIs('business.edit.*') && request()->route('business') && request()->route('business')->user_id !== auth()->id();
                            $isEditingOtherUserProfile = request()->routeIs('admin.users.edit.*') && request()->route('user') && request()->route('user')->id !== auth()->id();
                        @endphp
                        <flux:navlist.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users') || $isEditingOtherUserBusiness || $isEditingOtherUserProfile" wire:navigate>
                            {{ __('admin.users_navigation') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="credit-card" :href="route('admin.subscriptions')" :current="request()->routeIs('admin.subscriptions')" wire:navigate>
                            {{ __('admin.subscriptions_navigation') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endif

                @php
                    $business = auth()->user()->business;
                @endphp

                @if ($business)
                    <flux:navlist.group :heading="__('app.my_business')" class="grid">
                        @php
                            $isEditingOwnBusiness = request()->route('business') && request()->route('business')->user_id === auth()->id();
                        @endphp
                        <flux:navlist.item icon="pencil-square" :href="route('business.edit.data', $business)" :current="request()->routeIs('business.edit.data') && $isEditingOwnBusiness" wire:navigate>
                            {{ __('edit-business.information_nav') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="chat-bubble-left-right" :href="route('business.edit.whatsapp', $business)" :current="request()->routeIs('business.edit.whatsapp') && $isEditingOwnBusiness" wire:navigate>
                            {{ __('edit-business.whatsapp_nav') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="link" :href="route('business.edit.social-links', $business)" :current="request()->routeIs('business.edit.social-links') && $isEditingOwnBusiness"
                            wire:navigate>
                            {{ __('edit-business.social_links_nav') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="map-pin" :href="route('business.edit.location', $business)" :current="request()->routeIs('business.edit.location') && $isEditingOwnBusiness"
                            wire:navigate>
                            {{ __('edit-business.location_nav') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="document-text" :href="route('business.edit.documents', $business)" :current="request()->routeIs('business.edit.documents') && $isEditingOwnBusiness" wire:navigate>
                            {{ __('edit-business.documents_nav') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('app.settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('app.log_out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('app.settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('app.log_out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        @stack('scripts')
    </body>
</html>
