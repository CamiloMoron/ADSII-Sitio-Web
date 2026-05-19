@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Bienvenido, {{ auth()->user()->nombre }}</h2>
    <p class="text-sm mb-6" style="color:#64748b;">Panel de control · {{ auth()->user()->role->display_name }}</p>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $statsCards = [
                ['icon' => 'building-2', 'label' => 'Clientes', 'value' => $stats['clientes'], 'color' => '#3b82f6'],
                ['icon' => 'truck', 'label' => 'Vehículos', 'value' => $stats['vehiculos'], 'color' => '#10b981'],
                ['icon' => 'package', 'label' => 'Materiales', 'value' => $stats['materiales'], 'color' => '#8b5cf6'],
                ['icon' => 'users', 'label' => 'Usuarios', 'value' => $stats['usuarios'], 'color' => '#f59e0b'],
            ];
        @endphp

        @foreach($statsCards as $index => $stat)
            <div class="slide-in rounded-xl p-5" 
                 style="background:#1e293b;border:1px solid #334155;animation-delay:{{ $index * 0.08 }}s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" 
                         style="background:{{ $stat['color'] }}22;">
                        <i data-lucide="{{ $stat['icon'] }}" 
                           style="width:20px;height:20px;color:{{ $stat['color'] }};"></i>
                    </div>
                    <span class="mono text-2xl font-bold text-white">{{ $stat['value'] }}</span>
                </div>
                <span class="text-sm" style="color:#94a3b8;">{{ $stat['label'] }} registrados</span>
            </div>
        @endforeach
    </div>

    <!-- Role-based Access Panel -->
    <div class="rounded-xl p-6" style="background:#1e293b;border:1px solid #334155;">
        <h3 class="text-sm font-semibold text-white mb-4">Accesos según perfil (CUB01)</h3>
        <div class="space-y-2">
            @foreach($menuByRole as $roleName => $menus)
                <div class="flex items-center gap-3 text-sm py-2 px-3 rounded-lg" 
                     style="background:#0f172a;">
                    <span class="font-medium" style="color:#f59e0b;min-width:200px;">{{ $roleName }}</span>
                    <span style="color:#94a3b8;">
                        → {{ collect($menus)->pluck('label')->implode(', ') }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
