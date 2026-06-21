@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Nuevo Contrato</h2>
    <p class="text-sm mb-6" style="color:#64748b;">Registrar un nuevo contrato de servicio</p>

    @if($errors->any())
        <div class="mb-4 text-sm rounded-lg p-3 fade-in" style="background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl p-6" style="background:#1e293b;border:1px solid #334155;">
        <form action="{{ route('contratos.store') }}" method="POST" class="space-y-4 max-w-2xl">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Cliente</label>
                <select name="cliente_id" 
                        required 
                        class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                        style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                    <option value="">Seleccione un cliente...</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }} — {{ $cliente->ruc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Fecha de Inicio</label>
                    <input type="date" 
                           name="fecha_inicio" 
                           required 
                           value="{{ old('fecha_inicio') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Fecha de Fin</label>
                    <input type="date" 
                           name="fecha_fin" 
                           required 
                           value="{{ old('fecha_fin') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Tarifa (S/)</label>
                    <input type="number" 
                           name="tarifa" 
                           required 
                           step="0.01" 
                           min="0"
                           value="{{ old('tarifa') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Estado</label>
                    <select name="estado" 
                            required 
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Activo" {{ old('estado') === 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Vencido" {{ old('estado') === 'Vencido' ? 'selected' : '' }}>Vencido</option>
                        <option value="Suspendido" {{ old('estado') === 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Cláusulas (opcional)</label>
                <textarea name="clausulas" 
                          rows="4"
                          class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                          style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                          placeholder="Ingrese las cláusulas del contrato...">{{ old('clausulas') }}</textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110" 
                        style="background:#f59e0b;color:#0f172a;">
                    Guardar Contrato
                </button>
                <a href="{{ route('contratos.index') }}" 
                   class="flex-1 py-2.5 rounded-lg text-sm font-medium transition text-center" 
                   style="background:#334155;color:#94a3b8;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
