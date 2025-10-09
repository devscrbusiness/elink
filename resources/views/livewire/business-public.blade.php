<div class="max-w-md mx-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-lg font-sans overflow-hidden border border-gray-200 dark:border-zinc-700">
    {{-- Logo --}}
    <div class="flex flex-col items-center pt-8 pb-4 bg-white dark:bg-zinc-900">
        @if ($business->logo)
            <img class="h-32 w-32 rounded-full object-cover border-4 border-white dark:border-zinc-800 shadow-lg" src="{{ asset('storage/' . $business->logo) }}" alt="Logo de {{ $business->name }}">
        @endif
    </div>

    {{-- Nombre --}}
    <div class="text-center px-6 pb-4">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-200 mb-2">{{ $business->name }}</h1>
        @if ($business->description)
            <p class="text-gray-600 dark:text-gray-400">{{ $business->description }}</p>
        @endif
    </div>

    {{-- Línea separadora --}}
    <hr class="border-t-2 border-gray-100 dark:border-zinc-800">

    {{-- WhatsApp --}}
    @if($whatsapps->count())
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.contact_whatsapp_title') }}</h2>
            <div class="flex flex-col gap-3">
                @foreach($whatsapps as $wa)
                    @php
                        // Si tiene un slug personalizado, usa la ruta de redirección. Si no, la URL directa.
                        $url = $wa->custom_slug ? route('whatsapp.redirect', $wa->custom_slug) : $wa->url;
                    @endphp
                    <a href="{{ $url }}" wire:click.prevent="$dispatch('logClick', { linkId: {{ $wa->id }}, linkType: 'whatsapp' })" target="_blank" class="flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-gray-200 font-bold py-3 px-4 rounded-full text-lg transition">
                        <x-icons.social.whatsapp class="w-6 h-6" />
                        <span>{{ $wa->alias }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Redes Sociales --}}
    @if($socialNetworks->count())
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 text-center">{{ __('edit-business.our_networks_title') }}</h2>
            
            {{-- Cuadrícula de Redes Sociales --}}
            <div class="grid grid-cols-2 gap-3">
                @foreach($socialNetworks as $link)
                    @php
                        $colorClass = match($link->type) { 
                            'facebook' => 'bg-blue-600 hover:bg-blue-700',
                            'instagram' => 'bg-fuchsia-600 hover:bg-fuchsia-700',
                            'youtube' => 'bg-red-600 hover:bg-red-700',
                            'tiktok' => 'bg-black hover:bg-zinc-800',
                            'x' => 'bg-black hover:bg-zinc-800',
                            'linkedin' => 'bg-sky-700 hover:bg-sky-800',
                            'telegram' => 'bg-sky-500 hover:bg-sky-600',
                            default => 'bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600',
                        };

                        $displayText = ($link->alias && strtolower($link->alias) !== strtolower($link->type))
                            ? $link->alias
                            : ucfirst($link->type);
                    @endphp
                    <a href="{{ $link->url }}" wire:click.prevent="$dispatch('logClick', { linkId: {{ $link->id }}, linkType: 'social' })" target="_blank"
                       class="flex items-center justify-center gap-2 py-3 px-4 rounded-full text-lg font-semibold text-gray-200 transition {{ $colorClass }}">
                        <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6 text-gray-200" />
                        <span>{{ $displayText }}</span>
                    </a>
                @endforeach
            </div>
            
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Mails --}}
    @if($mails->count())
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.contact_mail_title') }}</h2>
            <div class="flex flex-col gap-3">
                @foreach($mails as $link)
                    @php
                        $displayEmail = str_replace('mailto:', '', $link->url);
                    @endphp
                    <a href="{{ $link->url }}" wire:click.prevent="$dispatch('logClick', { linkId: {{ $link->id }}, linkType: 'social' })" class="flex items-center justify-center gap-3 bg-gray-500 dark:bg-zinc-800 hover:bg-gray-800 dark:hover:bg-zinc-700 text-gray-200 dark:text-gray-200 font-bold py-3 px-4 rounded-full text-lg transition">
                        <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6" />
                        <span>{{ $link->alias ?: $displayEmail }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Sitios Web --}}
    @if($allWebsites->count() > 0)
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 text-center">
                {{ trans_choice('edit-business.website_title', $allWebsites->count()) }}
            </h2>
            <div class="flex flex-col gap-3">
                @foreach($allWebsites as $website)
                    @php
                        // Limpieza de la la URL para mostrarla sin http/https
                        $displayUrl = preg_replace('/^https?:\/\//', '', $website->url);
                    @endphp
                    <a href="{{ $website->url }}" wire:click.prevent="$dispatch('logClick', { linkId: {{ $website->id }}, linkType: 'social' })" target="_blank" class="flex items-center justify-center gap-3 bg-gray-500 dark:bg-zinc-800 hover:bg-gray-800 dark:hover:bg-zinc-700 text-gray-200 font-bold py-3 px-4 rounded-full text-lg transition">
                        <x-icons.social.website class="w-6 h-6" />
                        <span>{{ $website->alias ?: $displayUrl }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Otros Enlaces --}}
    @if($business->socialLinks->whereIn('type', ['other'])->where('is_public', true)->count())
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.other_links_title') }}</h2>
            <div class="flex flex-col gap-3">
                @foreach($business->socialLinks->whereIn('type', ['other'])->where('is_public', true) as $link)
                    <a href="{{ $link->url }}" wire:click.prevent="$dispatch('logClick', { linkId: {{ $link->id }}, linkType: 'social' })" target="_blank" class="flex items-center justify-center gap-3 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-800 dark:text-gray-200 font-bold py-3 px-4 rounded-full text-lg transition">
                        <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6" />
                        <span>{{ $link->alias }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Ubicación --}}
    @if ($location)
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.location_title') }}</h2>
            @if($location->detail)
                <p class="text-center text-gray-600 dark:text-gray-400 mb-4">{{ $location->detail }}</p>
            @endif
            <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-zinc-700">
                <iframe
                    width="100%"
                    height="220"
                    frameborder="0"
                    style="border:0"
                    src="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}&hl=es&z=16&output=embed"
                    allowfullscreen
                    aria-hidden="false"
                    tabindex="0"
                ></iframe>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('logClick', ({ linkId, linkType }) => {
                @this.call('logClick', linkId, linkType).then(url => {
                    if (url) {
                        window.open(url, '_blank');
                    }
                });
            });
        });
    </script>
    @endpush
</div>
