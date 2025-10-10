@php
    // Si es un admin editando, usa el layout de admin.
    // Si no, usa el layout de contenido de la app.
    $layout = $isAdminEditing ? 'admin.layout' : 'layouts.app-content';
@endphp
<x-dynamic-component :component="$layout" :business="$business" :isAdminEditing="$isAdminEditing">
    
<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="w-full max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('edit-business.location_form_title') }}</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ __('edit-business.location_form_subtitle') }}
        </p>

        @if (session()->has('message'))
            <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-8 space-y-6">
            <div x-data="mapManager($wire.latitude, $wire.longitude)" class="my-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('edit-business.location_map_label') }}
                </label>
                <div wire:ignore x-ref="map" id="map" style="height: 350px; border-radius: 0.5rem;"></div>
                @error('latitude') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                @error('longitude') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Detail -->
            <div>
                <label for="detail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('edit-business.location_detail_label') }}</label>
                <textarea wire:model.lazy="detail" id="detail" rows="4" placeholder="{{ __('edit-business.location_detail_placeholder') }}"
                          class="mt-1 block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600"></textarea>
                @error('detail') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('edit-business.save_button') }}
                    <div wire:loading wire:target="save" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"></div>
                </button>
            </div>
        </form>
    </div>
</x-dynamic-component>

@push('scripts')
    <script data-navigate-once>
        function mapManager(initialLat, initialLng) {
            return {
                map: null,
                marker: null,
                init() {
                    this.loadGoogleMaps().then(() => {
                        this.initializeMap(initialLat, initialLng);
                    })
                },
                loadGoogleMaps() {
                    return new Promise((resolve) => {
                        if (window.google && window.google.maps) {
                            return resolve();
                        }
                        window.initMap = () => resolve();
                        const script = document.createElement('script');
                        script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap`;
                        script.async = true;
                        script.defer = true;
                        document.head.appendChild(script);
                    });
                },
                initializeMap(lat, lng) {
                    const center = { lat: parseFloat(lat) || -0.2224093, lng: parseFloat(lng) || -78.5335029 };
                    this.map = new google.maps.Map(this.$refs.map, {
                        center: center,
                        zoom: 14,
                    });
                    this.marker = new google.maps.Marker({
                        position: center,
                        map: this.map,
                        draggable: true,
                    });
                    this.map.addListener('click', (e) => {
                        this.marker.setPosition(e.latLng);
                        this.$wire.set('latitude', e.latLng.lat());
                        this.$wire.set('longitude', e.latLng.lng());
                    });
                    this.marker.addListener('dragend', (e) => {
                        this.$wire.set('latitude', e.latLng.lat());
                        this.$wire.set('longitude', e.latLng.lng());
                    });
                }
            }
        }
    </script>
@endpush