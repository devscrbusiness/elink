<div class="max-w-md mx-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-lg barlow-semi-condensed-regular overflow-hidden border border-gray-200 dark:border-zinc-700">
    {{-- Selector de idioma --}}
    <div class="flex items-center justify-center gap-2 p-3">
        <a href="{{ route('locale.switch', 'es') }}"
           aria-pressed="{{ app()->getLocale() === 'es' ? 'true' : 'false' }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-full border font-semibold transition
                  {{ app()->getLocale() === 'es' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 dark:bg-zinc-800 dark:text-gray-200 dark:border-zinc-700' }}">
            <span class="w-5 h-5 inline-block" aria-hidden="true">
                <svg viewBox="0 0 60 36" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                    <rect width="60" height="36" fill="#c60b1e"/>
                    <rect y="8" width="60" height="20" fill="#ffc400"/>
                </svg>
            </span>
            <span class="sr-only">Español</span>
            <span>ES</span>
        </a>
        <a href="{{ route('locale.switch', 'en') }}"
           aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-full border font-semibold transition
                  {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 dark:bg-zinc-800 dark:text-gray-200 dark:border-zinc-700' }}">
            <span class="w-5 h-5 inline-block" aria-hidden="true">
                <svg viewBox="0 0 60 36" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                    <rect width="60" height="36" fill="#012169"/>
                    <g fill="#fff">
                        <rect x="26" width="8" height="36"/>
                        <rect y="14" width="60" height="8"/>
                    </g>
                    <g fill="#c8102e">
                        <rect x="28" width="4" height="36"/>
                        <rect y="16" width="60" height="4"/>
                    </g>
                    <g stroke="#fff" stroke-width="4">
                        <path d="M0 0 L60 36 M60 0 L0 36"/>
                    </g>
                    <g stroke="#c8102e" stroke-width="2">
                        <path d="M0 0 L60 36 M60 0 L0 36"/>
                    </g>
                </svg>
            </span>
            <span class="sr-only">English</span>
            <span>EN</span>
        </a>
    </div>

    {{-- Logo --}}
    <div class="flex flex-col items-center pt-8 pb-4 bg-white dark:bg-zinc-900">
        @if ($business->logo)
            <img class="h-32 w-32 rounded-full object-cover border-4 border-white dark:border-zinc-800 shadow-lg" src="{{ asset('storage/' . $business->logo) }}" alt="Logo de {{ $business->name }}">
        @endif
    </div>

    {{-- Nombre --}}
    <div class="text-center px-6 pb-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-200 mb-2">{{ $business->name }}</h1>
        @if ($business->description)
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $business->description }}</p>
        @endif
    </div>

    {{-- Línea separadora --}}
    <hr class="border-t-2 border-gray-100 dark:border-zinc-800">

    {{-- WhatsApp --}}
    @if($whatsapps->count())
        <div class="p-6">
            <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.contact_whatsapp_title') }}</h2>
            <div class="flex flex-col gap-3">
                @foreach($whatsapps as $wa)
                    @php
                        // Si tiene slug, usa la ruta de slug. Si no, la de ID para poder registrar el clic.
                        $url = $wa->custom_slug ? route('whatsapp.redirect', $wa->custom_slug) : route('whatsapp.redirect.id', $wa->id);
                    @endphp
                    <a href="{{ $url }}" target="_blank" class="flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-gray-200 font-bold py-3 px-4 rounded-full transition">
                        <x-icons.social.whatsapp class="w-6 h-6" />
                        <span class="select-none">{{ $wa->alias }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Redes Sociales --}}
    @if($socialNetworks->count())
        <div class="p-6">
            <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-4 text-center">{{ __('edit-business.our_networks_title') }}</h2>
            
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
                    <a href="{{ route('link.redirect', $link->id) }}" target="_blank"
                       class="flex items-center justify-center gap-2 py-3 px-4 rounded-full font-semibold text-gray-200 transition {{ $colorClass }}">
                        <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6 text-gray-200" />
                        <span class="select-none">{{ $displayText }}</span>
                    </a>
                @endforeach
            </div>

        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Mails --}}
    @if($mails->count())
        <div class="p-6">
            <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.contact_mail_title') }}</h2>
            <div class="flex flex-col gap-3">
                @foreach($mails as $link)
                    @php
                        $displayEmail = str_replace('mailto:', '', $link->url);
                    @endphp
                    <a href="{{ route('link.redirect', $link->id) }}" class="flex items-center justify-center gap-3 bg-gray-500 dark:bg-zinc-800 hover:bg-gray-800 dark:hover:bg-zinc-700 text-gray-200 dark:text-gray-200 font-bold py-3 px-4 rounded-full transition">
                        <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6" />
                        <span class="select-none">{{ $link->alias ?: $displayEmail }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Sitios Web --}}
    @if($websites->count() > 0)
        <div class="p-6">
            <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-4 text-center">
                {{ trans_choice('edit-business.website_title', $websites->count()) }}
            </h2>
            <div class="flex flex-col gap-3">
                @foreach($websites as $website)
                    @php
                        // Limpieza de la la URL para mostrarla sin http/https
                        $displayUrl = preg_replace('/^https?:\/\//', '', $website->url);
                    @endphp
                    <a href="{{ route('link.redirect', $website->id) }}" target="_blank" class="flex items-center justify-center gap-3 bg-gray-500 dark:bg-zinc-800 hover:bg-gray-800 dark:hover:bg-zinc-700 text-gray-200 font-bold py-3 px-4 rounded-full transition">
                        <x-icons.social.website class="w-6 h-6" />
                        <span class="select-none">{{ $website->alias ?: $displayUrl }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Otros Enlaces --}}
    @if($others->count())
        <div class="p-6">
            <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.other_links_title') }}</h2>
            <div class="flex flex-col gap-3">
                @foreach($others as $link)
                    <a href="{{ route('link.redirect', $link->id) }}" target="_blank" class="flex items-center justify-center gap-3 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-800 dark:text-gray-200 font-bold py-3 px-4 rounded-full transition">
                        <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6" />
                        <span class="select-none">{{ $link->alias }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
    @endif

    {{-- Ubicación --}}
    @if ($location)
        <div class="p-6">
            <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.location_title') }}</h2>
            @if($location->detail)
                <p class="text-center text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $location->detail }}</p>
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

</div>
