<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('settings.language_nav')" :subheading="__('settings.language_subtitle')">
        <flux:radio.group variant="segmented" wire:model.live="locale">
            <flux:radio value="es">{{ __('settings.spanish') }}</flux:radio>
            <flux:radio value="en">{{ __('settings.english') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>