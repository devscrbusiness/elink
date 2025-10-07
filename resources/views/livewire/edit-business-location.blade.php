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
            <div x-data="mapManager" x-init="init($wire)" class="my-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('edit-business.location_map_label') }}
                </label>
                <div wire:ignore id="map" style="height: 350px; border-radius: 0.5rem;"></div>
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
</div>

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap" async defer></script>
    <script>
        // Definimos la función de inicialización del mapa en el scope global
        // para que el callback de la API de Google pueda encontrarla.
        window.initMap = () => {
            // Disparamos un evento personalizado para notificar que la API está lista.
            // Esto desacopla la carga de la API de la inicialización de nuestros componentes.
            document.dispatchEvent(new CustomEvent('google-maps-ready'));
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('mapManager', () => {
                return {
                    map: null,
                    marker: null,
                    init($wire) {
                        const initialize = () => {
                            const lat = parseFloat($wire.get('latitude')) || -0.2224093;
                            const lng = parseFloat($wire.get('longitude')) || -78.5335029;
                            const center = { lat, lng };

                            this.map = new google.maps.Map(document.getElementById('map'), {
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
                                $wire.set('latitude', e.latLng.lat());
                                $wire.set('longitude', e.latLng.lng());
                            });

                            this.marker.addListener('dragend', (e) => {
                                $wire.set('latitude', e.latLng.lat());
                                $wire.set('longitude', e.latLng.lng());
                            });
                        }
                        
                        // Si la API de Google ya está lista, inicializa. Si no, espera el evento.
                        window.google && window.google.maps ? initialize() : document.addEventListener('google-maps-ready', initialize, { once: true });
                    }
                }
            });
        })
    </script>
@endpush