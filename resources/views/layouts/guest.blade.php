<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- RTL Support -->
        <style>
            [dir="rtl"] .text-start {
                text-align: right !important;
            }
            [dir="rtl"] .text-end {
                text-align: left !important;
            }
            [dir="rtl"] .ms-2 {
                margin-left: 0;
                margin-right: 0.5rem;
            }
            [dir="rtl"] .ms-4 {
                margin-left: 0;
                margin-right: 1rem;
            }
            [dir="rtl"] .me-4 {
                margin-right: 0;
                margin-left: 1rem;
            }
        </style>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
