<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FinanceZen') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 h-full flex flex-col items-center justify-center p-6">
        <div class="w-full max-w-md">
            <!-- Logo area -->
            <div class="text-center mb-10">
                <div class="mx-auto w-16 h-16 bg-primary rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-primary/30 mb-6">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
                <h2 class="text-3xl font-black tracking-tight text-slate-900">FinanceZen</h2>
                <p class="text-slate-500 font-medium mt-2">Votre compagnon de gestion financière.</p>
            </div>

            <!-- Card container -->
            <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-100 relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1.5 bg-primary/20"></div>
                
                {{ $slot }}
            </div>

            <!-- Footer links -->
            <div class="text-center mt-10">
                <p class="text-sm font-medium text-slate-500 italic">© 2026 FinanceZen SaaS.</p>
            </div>
        </div>

        <script>lucide.createIcons();</script>
    </body>
</html>
