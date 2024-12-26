<!DOCTYPE html>
<html data-theme="mytheme" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CUE') }}</title>

        <!-- Fonts -->

        <!-- Scripts -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased bg-base-300">

    {{-- The navbar with `sticky` and `full-width` --}}
    <livewire:layout.navigation />


    {{-- The main content with `full-width` --}}
    <main class="px-5 md:px-60 mt-20">
        <div class="max-w-screen-sm mx-auto">
            {{ $slot }}
        </div>
    </main>

    {{--  TOAST area --}}
    <x-mary-toast />
    </body>
</html>
