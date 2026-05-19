<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'Space Mono', monospace; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideIn { from { opacity:0; transform:translateX(-20px); } to { opacity:1; transform:translateX(0); } }
        .fade-in { animation: fadeIn 0.4s ease both; }
        .slide-in { animation: slideIn 0.35s ease both; }
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-thumb { background:#334155; border-radius:3px; }
    </style>
</head>
<body class="h-full">
    <div id="app" class="h-full w-full overflow-auto" style="background:#0f172a;">
        <div class="h-full w-full">
            <div class="flex h-full">
                <!-- SIDEBAR -->
                <aside class="w-64 flex-shrink-0 flex flex-col h-full" style="background:#1e293b;border-right:1px solid #334155;">
                    <!-- Logo -->
                    <div class="p-5 flex items-center gap-3" style="border-bottom:1px solid #334155;">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background:#f59e0b;">
                            <i data-lucide="shield-check" style="width:18px;height:18px;color:#0f172a;"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">SGR</div>
                            <div class="text-xs" style="color:#64748b;">EcoResíduos SAC</div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 p-3 space-y-1 overflow-auto">
                        @php
                            $menuItems = config('menu.' . auth()->user()->role->name, []);
                            $currentRoute = request()->route()->getName();
                        @endphp

                        @foreach($menuItems as $item)
                            <a href="{{ route($item['route']) }}" 
                               class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ $currentRoute === $item['route'] ? 'active-menu-item' : '' }}"
                               style="{{ $currentRoute === $item['route'] ? 'background:#f59e0b22;color:#f59e0b;' : 'color:#94a3b8;' }}">
                                <i data-lucide="{{ $item['icon'] }}" style="width:18px;height:18px;"></i>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <!-- User Info & Logout -->
                    <div class="p-4" style="border-top:1px solid #334155;">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" 
                                 style="background:#f59e0b;color:#0f172a;">
                                {{ auth()->user()->initials }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">{{ auth()->user()->nombre }}</div>
                                <div class="text-xs" style="color:#64748b;">{{ auth()->user()->role->display_name }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-2 justify-center py-2 rounded-lg text-xs font-medium transition hover:brightness-110" 
                                    style="background:#0f172a;color:#94a3b8;border:1px solid #334155;">
                                <i data-lucide="log-out" style="width:14px;height:14px;"></i> 
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- CONTENT -->
                <main class="flex-1 overflow-auto p-6" style="background:#0f172a;">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
