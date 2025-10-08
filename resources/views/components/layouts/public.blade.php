<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-zinc-900 p-4">
        {{ $slot }}

        @fluxScripts
        @stack('scripts')
    </body>
</html>