<div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-md font-sans">
    <div class="flex flex-col md:flex-row items-center">
        {{-- Logo de la empresa --}}
        @if ($business->logo_path)
            <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                <img class="h-24 w-24 rounded-full object-cover ring-2 ring-gray-300" src="{{ asset('storage/' . $business->logo_path) }}" alt="Logo de {{ $business->name }}">
            </div>
        @endif

        <div class="flex-grow text-center md:text-left">
            {{-- Nombre de la empresa --}}
            <h1 class="text-3xl font-bold text-gray-900">{{ $business->name }}</h1>

            {{-- Sitio web --}}
            @if ($business->website)
                <a href="{{ $business->website }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out">
                    {{ $business->website }}
                </a>
            @endif
        </div>
    </div>

    {{-- Descripción --}}
    @if ($business->description)
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Sobre nosotros</h2>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $business->description }}</p>
        </div>
    @endif

    {{-- Redes Sociales --}}
    @if ($business->social_networks && count((array) $business->social_networks) > 0)
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Síguenos en nuestras redes</h2>
            <div class="flex items-center space-x-4">
                @foreach ($business->social_networks as $network => $url)
                    @if($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($network) }}" class="text-gray-500 hover:text-gray-800 transition duration-300 ease-in-out">
                            {{-- Aquí puedes usar SVGs para los iconos de redes sociales --}}
                            <span class="h-8 w-8">
                                {{-- Ejemplo para un icono SVG (reemplaza con tus propios iconos) --}}
                                <x-icon name="{{ $network }}" class="h-8 w-8" />
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
