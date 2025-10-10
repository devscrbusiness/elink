<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('settings.appearance_nav')" :subheading="__('settings.appearance_subtitle')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('settings.light_theme') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('settings.dark_theme') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('settings.system_theme') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
