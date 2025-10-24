@php
    // Si es un admin editando, usa el layout de admin.
    // Si no, usa el layout de contenido de la app.
    $layout = $isAdminEditing ? 'admin.layout' : 'layouts.app-content';
@endphp
<x-dynamic-component :component="$layout" :user="$user" :business="$business" :isAdminEditing="$isAdminEditing">
    
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

        <div class="mt-8 space-y-6 border-b dark:border-zinc-700 pb-8 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{ is_null($editingIndex) ? __('edit-business.add_location_button') : __('edit-business.edit_location_heading') }}
            </h3>

            <div x-data="mapManager(@js($editingLocation['latitude']), @js($editingLocation['longitude']))"
                 x-init="init()"
                 @location-selected.window="updateMarker($event.detail.latitude, $event.detail.longitude)"
                 class="my-6">
                <!-- Search Input -->
                <div class="mb-4">
                    <input x-ref="searchBox" type="text" placeholder="{{ __('map.search_location_placeholder') }}"
                           class="block w-full px-4 py-3 text-gray-900 bg-gray-100 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                </div>

                <!-- Map -->
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('edit-business.location_map_label') }}
                </label>
                <div wire:ignore x-ref="map" style="height: 350px; border-radius: 0.5rem;"></div>
                @error('editingLocation.latitude') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                @error('editingLocation.longitude') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Detail -->
            <div>
                <flux:input wire:model.lazy="editingLocation.detail" id="detail" type="textarea" :label="__('edit-business.location_detail_label')" :placeholder="__('edit-business.location_detail_placeholder')" />
                @error('editingLocation.detail') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-4">
                @if(!is_null($editingIndex))
                    <button type="button" wire:click="prepareNewLocation" class="px-6 py-3 font-semibold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-zinc-600 dark:text-gray-200 dark:hover:bg-zinc-500">
                        {{ __('edit-business.cancel_button') }}
                    </button>
                @endif
                <button type="button" wire:click="saveLocation" class="px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ is_null($editingIndex) ? __('edit-business.add_location_button') : __('edit-business.save_button') }}
                </button>
            </div>
        </div>

        <!-- Lista de ubicaciones -->
        <div class="space-y-4">
            @forelse($locations as $index => $location)
                <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-zinc-900/50 group">
                    <div class="flex items-center gap-3">
                        <x-icon name="map-pin" class="w-6 h-6 text-gray-400" />
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $location['detail'] ?: __('edit-business.location_no_detail') }}</span>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="selectForEditing({{ $index }})" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="{{ __('edit-business.edit_button') }}"><x-icon name="pencil" class="w-5 h-5" /></button>
                        <button wire:click="removeLocation({{ $location['id'] }})" wire:confirm="{{ __('edit-business.location_delete_confirmation') }}" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors" title="{{ __('edit-business.delete_button') }}"><x-icon name="trash" class="w-5 h-5" /></button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    {{ __('edit-business.no_locations_added') }}
                </div>
            @endforelse
        </div>
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
                        script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap`;
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
                        this.$wire.set(`editingLocation.latitude`, e.latLng.lat());
                        this.$wire.set(`editingLocation.longitude`, e.latLng.lng());
                    });
                    this.marker.addListener('dragend', (e) => {
                        this.$wire.set(`editingLocation.latitude`, e.latLng.lat());
                        this.$wire.set(`editingLocation.longitude`, e.latLng.lng());
                    });

                    // Search Box
                    const searchBox = new google.maps.places.SearchBox(this.$refs.searchBox);
                    this.map.addListener('bounds_changed', () => {
                        searchBox.setBounds(this.map.getBounds());
                    });

                    searchBox.addListener('places_changed', () => {
                        const places = searchBox.getPlaces();
                        if (places.length == 0) {
                            return;
                        }
                        const place = places[0];
                        if (!place.geometry || !place.geometry.location) {
                            return;
                        }
                        this.map.setCenter(place.geometry.location);
                        this.marker.setPosition(place.geometry.location);
                        this.$wire.set(`editingLocation.latitude`, place.geometry.location.lat());
                        this.$wire.set(`editingLocation.longitude`, place.geometry.location.lng());
                    });
                },
                updateMarker(lat, lng) {
                    const newPosition = new google.maps.LatLng(lat, lng);
                    this.marker.setPosition(newPosition);
                    this.map.setCenter(newPosition);
                },
            }
        }
    </script>
@endpush