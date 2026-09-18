<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FinApp') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 flex h-screen overflow-hidden">

    <!-- SIDEBAR ESTILO "UNTITLED UI" -->
<!-- SIDEBAR ESTILO "UNTITLED UI" (Light & Dark Mode) -->
<aside class="w-[280px] bg-white dark:bg-[#101828] border-r border-gray-200 dark:border-white/10 flex-col hidden md:flex h-screen z-20 flex-shrink-0 transition-colors duration-300">

    <!-- 1. Botones estilo macOS -->
    <div class="px-6 pt-5 pb-4 flex gap-2">
        <div class="w-3 h-3 rounded-full bg-[#FF5F56] border border-[#E0443E] dark:border-transparent"></div>
        <div class="w-3 h-3 rounded-full bg-[#FFBD2E] border border-[#DEA123] dark:border-transparent"></div>
        <div class="w-3 h-3 rounded-full bg-[#27C93F] border border-[#1AAB29] dark:border-transparent"></div>
    </div>

    <!-- 2. Logo y Nombre de la App -->
    <div class="px-6 pb-6 flex items-center gap-3">
        <!-- Ícono degradado moderno -->
        <div class="relative flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 shadow-sm overflow-hidden">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-sm -rotate-45 scale-150 translate-y-2"></div>
            <svg class="w-5 h-5 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <span class="text-[17px] font-bold text-gray-900 dark:text-white tracking-tight">FinApp</span>
    </div>

    <!-- 3. Menú de Navegación -->
    <nav class="flex-1 px-4 overflow-y-auto space-y-6">

        <!-- GRUPO: DASHBOARD -->
        <div>
            <p class="px-3 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Dashboard</p>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-white/10 text-indigo-700 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white font-medium' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-sm">Overview</span>
                </a>
            </div>
        </div>

        <!-- GRUPO: FINANZAS -->
        <div>
            <p class="px-3 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Finanzas</p>
            <div class="space-y-1">
                
                <!-- Movimientos -->
                <a href="{{ route('transactions') }}" 
                   class="flex items-center justify-between px-3 py-2 rounded-md transition-colors {{ request()->routeIs('transactions') ? 'bg-indigo-50 dark:bg-white/10 text-indigo-700 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white font-medium' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->routeIs('transactions') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="text-sm">Movimientos</span>
                    </div>
                </a>

                <!-- Mis Cuentas -->
                <a href="{{ route('wallets') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('wallets') ? 'bg-indigo-50 dark:bg-white/10 text-indigo-700 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white font-medium' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('wallets') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    <span class="text-sm">Mis Cuentas</span>
                </a>

                <!-- Categorías -->
                <a href="{{ route('categories') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('categories') ? 'bg-indigo-50 dark:bg-white/10 text-indigo-700 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white font-medium' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('categories') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span class="text-sm">Categorías</span>
                </a>

            </div>
        </div>
    </nav>

    <!-- 4. Controles (Tema y Settings) -->
    <div class="px-4 pb-4 space-y-1" x-data>
        
        <!-- Botón Modo Claro / Oscuro -->
        <button @click="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light'" 
                class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors font-medium text-sm">
            <!-- Icono Luna (Visible en modo claro) -->
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <!-- Icono Sol (Visible en modo oscuro) -->
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span class="block dark:hidden">Modo Oscuro</span>
            <span class="hidden dark:block">Modo Claro</span>
        </button>

        <!-- Botón Settings -->
        <a href="{{ route('profile.show') }}" 
           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('profile.show') ? 'bg-indigo-50 dark:bg-white/10 text-indigo-700 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white font-medium' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('profile.show') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="text-sm">Settings</span>
        </a>
    </div>

    <!-- 5. Perfil de Usuario -->
    <div class="p-4 border-t border-gray-200 dark:border-white/10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col truncate">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</span>
                    <span class="text-[13px] text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit" class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Cerrar Sesión">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>

</aside>

    <!-- ÁREA DE CONTENIDO PRINCIPAL (Scrollable) -->
    <main class="flex-1 overflow-y-auto bg-gray-50/50">
        <!-- Topbar simple para mobile -->
        <header class="md:hidden bg-white h-16 border-b flex items-center px-4 justify-between">
            <span class="font-bold text-gray-800">FinApp</span>
            <!-- Aquí podrías poner un botón de hamburguesa -->
        </header>

        <!-- El contenido dinámico de cada página entra aquí -->
        <div class="p-6 md:p-10 max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>