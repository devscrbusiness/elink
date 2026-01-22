<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-zinc-900 p-4">
        {{ $slot }}

        @fluxScripts

        <footer class="p-4 text-center text-gray-500 dark:text-gray-400 text-xs">
            Copyright © {{ date('Y') }}. All rights reserved
            <br> <a href="/"><img src="{{ asset('elinklogo.svg') }}" alt="eLink Logo" class="w-24 m-2 inline"></a>
        </footer>

        @stack('scripts')
    </body>
</html>