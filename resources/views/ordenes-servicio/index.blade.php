@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Órdenes de Servicio</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB04 — Solicitudes de recojo de residuos</p>

    @if(session('success'))
        <div class="mb-4 text-sm rounded-lg p-3 fade-in" style="background:#065f4622;color:#34d399;border:1px solid #065f46;">
            <span class="flex items-center gap-2">
                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                {{ session('success') }}
            </span>
        </div>
    @endif

    <div class="rounded-xl overflow-hidden" style="background:#1e293b;border:1px solid #334155;">
        <div class="flex items-center justify-between p-4" style="border-bottom:1px solid #334155;">
            <span class="text-sm font-semibold text-white">{{ $ordenes->count() }} órdenes</span>
            <button onclick="openNewForm()"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110"
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Nueva Orden
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Cliente</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Sede</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Ventana</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Volumen</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Fecha</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenes as $orden)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">OS-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-white">{{ $orden->cliente->nombre }}</td>
                            <td class="px-4 py-3 text-white">{{ $orden->sede }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $orden->ventana_horaria }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $orden->volumen_estimado }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $orden->fecha->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $estadoStyle = match($orden->estado) {
                                        'Aprobada', 'Completada' => 'background:#065f4622;color:#34d399;border:1px solid #065f46;',
                                        'En Ruta' => 'background:#1e3a5f22;color:#93c5fd;border:1px solid #1e3a5f;',
                                        'Pendiente' => 'background:#f59e0b22;color:#f59e0b;border:1px solid #f59e0b;',
                                        'Cancelada' => 'background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;',
                                        default => 'background:#33415522;color:#94a3b8;border:1px solid #334155;',
                                    };
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium"
                                      style="{{ $estadoStyle }}">
                                    {{ $orden->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $orden->id }})"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110"
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('ordenes-servicio.destroy', $orden) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Está seguro de eliminar esta orden?');"
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
                            <td colspan="8" class="px-4 py-8 text-center" style="color:#64748b;">
                                No hay órdenes de servicio registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Orden -->
    <div id="new-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Nueva Orden de Servicio</h3>
            <form action="{{ route('ordenes-servicio.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Cliente</label>
                    <select name="cliente_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Sede de Recojo</label>
                    <input name="sede"
                           required
                           value="{{ old('sede') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Ej. Zona Industrial">
                    @error('sede')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Ventana Horaria</label>
                    <input name="ventana_horaria"
                           required
                           value="{{ old('ventana_horaria') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Ej. 08:00-10:00">
                    @error('ventana_horaria')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Volumen Estimado</label>
                    <input name="volumen_estimado"
                           required
                           value="{{ old('volumen_estimado') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Ej. 2 m³">
                    @error('volumen_estimado')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Fecha</label>
                    <input name="fecha"
                           type="date"
                           required
                           value="{{ old('fecha', date('Y-m-d')) }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                    @error('fecha')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110"
                            style="background:#f59e0b;color:#0f172a;">
                        Crear OS
                    </button>
                    <button type="button"
                            onclick="closeNewForm()"
                            class="flex-1 py-2.5 rounded-lg text-sm font-medium transition"
                            style="background:#334155;color:#94a3b8;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Overlay for Edit Orden -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Orden de Servicio</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Cliente</label>
                    <select name="cliente_id"
                            id="edit-cliente_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Sede de Recojo</label>
                    <input name="sede"
                           id="edit-sede"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Ventana Horaria</label>
                    <input name="ventana_horaria"
                           id="edit-ventana_horaria"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Volumen Estimado</label>
                    <input name="volumen_estimado"
                           id="edit-volumen_estimado"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Fecha</label>
                    <input name="fecha"
                           id="edit-fecha"
                           type="date"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Estado</label>
                    <select name="estado"
                            id="edit-estado"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Aprobada">Aprobada</option>
                        <option value="En Ruta">En Ruta</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110"
                            style="background:#f59e0b;color:#0f172a;">
                        Actualizar
                    </button>
                    <button type="button"
                            onclick="closeEditForm()"
                            class="flex-1 py-2.5 rounded-lg text-sm font-medium transition"
                            style="background:#334155;color:#94a3b8;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const ordenes = @json($ordenes);

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const orden = ordenes.find(o => o.id === id);
        if (!orden) return;

        document.getElementById('edit-cliente_id').value = orden.cliente_id;
        document.getElementById('edit-sede').value = orden.sede;
        document.getElementById('edit-ventana_horaria').value = orden.ventana_horaria;
        document.getElementById('edit-volumen_estimado').value = orden.volumen_estimado;
        document.getElementById('edit-fecha').value = orden.fecha;
        document.getElementById('edit-estado').value = orden.estado;

        document.getElementById('edit-form').action = `/ordenes-servicio/${id}`;
        document.getElementById('edit-form-overlay').classList.remove('hidden');
    }

    function closeEditForm() {
        document.getElementById('edit-form-overlay').classList.add('hidden');
    }

    @if($errors->any() && old('_method') === null)
        openNewForm();
    @endif

    lucide.createIcons();
</script>
@endsection
