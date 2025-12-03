<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:grid-rows-[50vh_minmax(0,1fr)]">

        {{-- Sección de la imagen principal --}}
        <img src="{{ asset('login-image.png') }}" class="order-1 md:order-none md:row-span-1 md:col-span-1 lg:col-span-2 h-full w-full object-cover max-h-[50vh] md:max-h-none">

        {{-- Sección de Formulario --}}
        <div class="order-2 md:order-none md:row-span-2 md:col-start-2 lg:col-start-3 flex items-start justify-center p-8 bg-white shadow-lg">
            <div class="w-full max-w-md px-6 py-4 lg:pt-24">
                <div class="w-full mt-6">
                    <img src="{{ asset('elinklogo.svg') }}" alt="DBCARD Logo" class="mx-auto lg:h-16 w-auto mb-8">
                    {{ $slot }}
                </div>
            </div>
        </div>

        {{-- Sección de las tarjetas --}}
        <div class="order-3 md:order-none md:row-start-2 md:col-span-1 lg:col-span-2 text-white font-family-barlow-condensed overflow-hidden p-6 lg:px-8 md:pt-8 relative bg-gradient-to-tr from-blue-600 via-blue-600 to-green-600">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl font-bold text-white mb-4 text-center md:text-left">Nuestros Clientes</h2>
                <div class="swiper mySwiper h-full">
                    <div class="swiper-wrapper pb-12">
                        @if(isset($usuarios) && $usuarios->isNotEmpty())
                        @foreach ($usuarios as $usuario)
                        <div class="swiper-slide">
                            <div class="flex flex-col h-full">
                                <div class="bg-white/90 backdrop-blur-sm p-6 lg:px-8 rounded-3xl shadow-lg text-center flex-grow flex flex-col min-h-64">
                                    <div class="flex justify-center mb-4">
                                        @if($usuario->business->logo)
                                        <img src="{{ asset('storage/' . $usuario->business->logo) }}" alt="Logo de {{ $usuario->business->name }}" class="h-24 w-24 rounded-full object-cover ring-2 ring-blue-500">
                                        @else
                                        <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center ring-2 ring-blue-500">
                                            <span class="text-gray-500 text-xs text-center">Sin logo</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="text-xl font-bold text-gray-800">{{ $usuario->business->name }}</h3>
                                        <p class="mt-2 text-sm text-gray-600">{{ Str::limit($usuario->business->description, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next text-white"></div>
                    <div class="swiper-button-prev text-white"></div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts

    <script>
        document.addEventListener('livewire:navigated', () => {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                breakpoints: {
                    // when window width is >= 1024px
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30
                    }
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });
        })
    </script>
</body>

</html>
