<div class="max-w-md mx-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-lg barlow-semi-condensed-regular overflow-hidden border border-gray-200 dark:border-zinc-700">
    @php
        $subscription = $business->user->latestSubscription;
        if (!$subscription || ($subscription->ends_at && $subscription->ends_at->isPast())) {
            abort(404);
        }
    @endphp

    @php
        // Define el orden por defecto de las secciones
        $defaultSectionOrder = [
            'whatsapp',
            'social_networks',
            'mails',
            'sms',
            'websites',
            'others',
            'documents',
            'location',
        ];
        
        $orderedSections = $business->section_order ?? $defaultSectionOrder;
    @endphp

    {{-- Selector de idioma --}}
    <div class="flex items-center justify-center gap-2 p-3">
        <a href="{{ route('locale.switch', 'es') }}"
           aria-pressed="{{ app()->getLocale() === 'es' ? 'true' : 'false' }}"
           class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full border font-semibold transition text-[0.5rem] sm:gap-1.5 sm:px-2 sm:py-1 md:gap-2 md:px-3 md:py-2 md:text-sm
                  {{ app()->getLocale() === 'es' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 dark:bg-zinc-800 dark:text-gray-200 dark:border-zinc-700' }}">
            <span class="w-2.5 h-2.5 inline-block sm:w-3 sm:h-3 md:w-4 md:h-4" aria-hidden="true">
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
           class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full border font-semibold transition text-[0.5rem] sm:gap-1.5 sm:px-2 sm:py-1 md:gap-2 md:px-3 md:py-2 md:text-sm
                  {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 dark:bg-zinc-800 dark:text-gray-200 dark:border-zinc-700' }}">
            <span class="w-2.5 h-2.5 inline-block sm:w-3 sm:h-3 md:w-4 md:h-4" aria-hidden="true">
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

    {{-- Renderizar secciones en el orden definido --}}
    @foreach ($orderedSections as $sectionKey)
        @switch($sectionKey)
            @case('whatsapp')
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
                        <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-3">
                            {{ __('edit-business.sms_ios_help') }}
                        </p>
                    </div>
                    <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
                @endif
                @break

            @case('social_networks')
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
                @break

            @case('mails')
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
                @break

            @case('sms')
                @if($sms->count())
                    <div class="p-6">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ __('edit-business.contact_sms_title') }}</h2>
                        <div class="flex flex-col gap-3">
                            @foreach($sms as $link)
                                @php
                                    $displaySms = str_replace('sms:', '', $link->url);
                                @endphp
                                <a href="{{ $link->url }}" class="flex items-center justify-center gap-3 bg-gray-500 dark:bg-zinc-800 hover:bg-gray-800 dark:hover:bg-zinc-700 text-gray-200 font-bold py-3 px-4 rounded-full transition">
                                    <x-dynamic-component :component="'icons.social.' . $link->type" class="w-6 h-6" />
                                    <span class="select-none">{{ $link->alias ?: $displaySms }}</span>
                                </a>
                            @endforeach
                        </div>
                        <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-3">
                            {{ __('edit-business.sms_ios_help') }}
                        </p>
                    </div>
                    <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
                @endif
                @break

            @case('websites')
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
                @break

            @case('others')
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
                @break

            @case('documents')
                @if($business->documents->count())
                    <div class="p-6">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ trans_choice('edit-business.documents_title', $business->documents->count()) }}</h2>
                        <div class="flex flex-col gap-3">
                            @foreach($business->documents as $document)
                                <a href="{{ route('document.redirect', $document) }}" target="_blank"
                                   class="flex items-center justify-center gap-3 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-800 dark:text-gray-200 font-bold py-3 px-4 rounded-full transition">
                                    <x-icon name="document-text" class="w-6 h-6" />
                                    <span class="select-none">{{ $document->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
                @endif
                @break

            @case('location')
                @if ($business->locations->count() > 0)
                    <div class="p-6" x-data="multiLocationMap(@js($business->locations))" x-init="initMap()">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 mb-3 text-center">{{ trans_choice('edit-business.location_title', $business->locations->count()) }}</h2>
                        
                        <div wire:ignore class="my-6">
                            <div x-ref="map" style="height: 350px; border-radius: 0.5rem;" class="rounded-lg overflow-hidden border border-gray-200 dark:border-zinc-700"></div>
                        </div>

                        <div class="space-y-2">
                            @foreach($business->locations as $index => $location)
                                <div @click="selectLocation({{ $index }})"
                                     class="p-3 rounded-lg cursor-pointer transition flex items-center justify-between"
                                     :class="{ 'bg-blue-100 dark:bg-blue-900/50 ring-2 ring-blue-500': selectedLocationIndex === {{ $index }}, 'bg-gray-50 dark:bg-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-700': selectedLocationIndex !== {{ $index }} }">
                                    <div class="flex items-center gap-3">
                                        <x-icon name="map-pin" class="w-5 h-5 text-gray-400" />
                                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $location->detail ?: __('edit-business.location_no_detail') }}</p>
                                    </div>
                                </div>
                            @endforeach
                            <div class="mt-4" x-show="selectedLocationIndex !== null" x-cloak>
                                <a :href="googleMapsUrl" target="_blank" class="flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-full transition w-full">
                                    <x-icon name="map" class="w-6 h-6" />
                                    <span class="select-none">{{ __('edit-business.view_on_google_maps') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="border-t-2 border-gray-100 dark:border-zinc-800">
                @endif
                @break
        @endswitch
    @endforeach

    {{-- Botón flotante para compartir --}}
    <div class="fixed bottom-6 right-6 z-50">
        <flux:modal.trigger name="share-public-profile-modal">
            <button
                class="flex items-center justify-center w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-transform transform hover:scale-110"
                title="{{ __('dashboard.share_profile') }}"
            >
                <x-icon name="share" class="w-7 h-7" />
                <span class="sr-only">{{ __('dashboard.share_profile') }}</span>
            </button>
        </flux:modal.trigger>
    </div>

    {{-- Modal para compartir --}}
    @php
        $publicUrl = url()->current();
        $shareText = urlencode("¡Mira este perfil en eLink! " . $publicUrl);
        $shareUrl = urlencode($publicUrl);

        $shareLinks = [
            'WhatsApp' => "https://api.whatsapp.com/send?text={$shareText}",
            'Facebook' => "https://www.facebook.com/sharer/sharer.php?u={$shareUrl}",
            'X' => "https://twitter.com/intent/tweet?url={$shareUrl}&text={$shareText}",
            'Telegram' => "https://t.me/share/url?url={$shareUrl}&text={$shareText}",
            'LinkedIn' => "https://www.linkedin.com/shareArticle?mini=true&url={$shareUrl}",
        ];

        $socialColors = [
            'WhatsApp' => 'bg-green-500 hover:bg-green-600',
            'Facebook' => 'bg-blue-600 hover:bg-blue-700',
            'X' => 'bg-black hover:bg-zinc-800',
            'Telegram' => 'bg-sky-500 hover:bg-sky-600',
            'LinkedIn' => 'bg-sky-700 hover:bg-sky-800',
        ];
    @endphp
    <flux:modal name="share-public-profile-modal" class="min-w-[24rem]">
        <flux:heading size="lg">{{ __('dashboard.share_profile') }}</flux:heading>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
            @foreach($shareLinks as $network => $link)
                <a href="{{ $link }}" target="_blank"
                   class="flex items-center justify-center gap-3 py-3 px-4 rounded-full font-semibold text-white transition {{ $socialColors[$network] ?? 'bg-gray-500 hover:bg-gray-600' }}">
                    <x-dynamic-component :component="'icons.social.' . strtolower($network)" class="w-6 h-6" />
                    <span>{{ __('dashboard.share_on', ['network' => $network]) }}</span>
                </a>
            @endforeach
        </div>
    </flux:modal>
</div>

@push('scripts')
<script data-navigate-once>
    function multiLocationMap(locations) {
        return {
            map: null,
            markers: [],
            locations: locations,
            selectedLocationIndex: null,
            googleMapsUrl: '',

            initMap() {
                this.loadGoogleMaps().then(() => {
                    this.initializeMap();
                });
            },

            loadGoogleMaps() {
                return new Promise((resolve) => {
                    if (window.google && window.google.maps) {
                        return resolve();
                    }
                    window.initMapCallback = () => resolve();
                    const script = document.createElement('script');
                    script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMapCallback`;
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                });
            },

            initializeMap() {
                if (this.locations.length === 0) { return; }

                const bounds = new google.maps.LatLngBounds();
                
                this.map = new google.maps.Map(this.$refs.map, {
                    mapTypeId: 'roadmap',
                });

                this.locations.forEach((
                    locationData,
                    index
                ) => {
                    const position = { lat: parseFloat(locationData.latitude), lng: parseFloat(locationData.longitude) };
                    const marker = new google.maps.Marker({
                        position: position,
                        map: this.map,
                        title: locationData.detail || '{{ $business->name }}',
                        animation: null
                    });

                    marker.addListener('click', () => {
                        this.selectLocation(index);
                    });

                    this.markers.push(marker);
                    bounds.extend(marker.getPosition());
                });

                this.map.fitBounds(bounds);

                // Si solo hay una ubicación, la seleccionamos por defecto
                if (this.locations.length === 1) {
                    this.selectLocation(0);
                }
            },

            selectLocation(index) {
                this.selectedLocationIndex = index;
                const location = this.locations[index];
                this.googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${location.latitude},${location.longitude}`;
                
                this.markers.forEach((m, i) => m.setAnimation(
                    i === index ? google.maps.Animation.BOUNCE : null
                ));

                this.map.panTo(this.markers[index].getPosition());
            }
        }
    }
</script>
@endpush
