@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Gestión de Contratos</h2>
    <p class="text-sm mb-6" style="color:#64748b;">Contratos de servicio con clientes</p>

    @include('catalogos._tabs')

    @if(session('success'))
        <div class="mb-4 text-sm rounded-lg p-3 fade-in" style="background:#065f4622;color:#34d399;border:1px solid #065f46;">
            <span class="flex items-center gap-2">
                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 text-sm rounded-lg p-3 fade-in" style="background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;">
            <span class="flex items-center gap-2">
                <i data-lucide="alert-circle" style="width:16px;height:16px;"></i>
                {{ session('error') }}
            </span>
        </div>
    @endif

    <div class="rounded-xl overflow-hidden" style="background:#1e293b;border:1px solid #334155;">
        <div class="flex items-center justify-between p-4" style="border-bottom:1px solid #334155;">
            <span class="text-sm font-semibold text-white">{{ $contratos->count() }} registros</span>
            <a href="{{ route('contratos.create') }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110" 
               style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Nuevo Contrato
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Cliente</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Vigencia</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Tarifa</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contratos as $contrato)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 text-white">{{ $contrato->id }}</td>
                            <td class="px-4 py-3 text-white">{{ $contrato->cliente->nombre }}</td>
                            <td class="px-4 py-3 mono text-xs" style="color:#94a3b8;">
                                {{ $contrato->fecha_inicio->format('d/m/Y') }} — {{ $contrato->fecha_fin->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">S/ {{ number_format($contrato->tarifa, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium" 
                                      style="{{ $contrato->estado === 'Activo' ? 'background:#065f4622;color:#34d399;border:1px solid #065f46;' : ($contrato->estado === 'Vencido' ? 'background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;' : 'background:#78350f22;color:#fbbf24;border:1px solid #78350f;') }}">
                                    {{ $contrato->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('contratos.edit', $contrato) }}" 
                                       class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110" 
                                       style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </a>
                                    <form action="{{ route('contratos.destroy', $contrato) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Está seguro de eliminar este contrato?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110" 
                                                style="background:#7f1d1d44;color:#fca5a5;">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr style="border-top:1px solid #334155;">
                            <td colspan="6" class="px-4 py-8 text-center" style="color:#64748b;">
                                No hay contratos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
