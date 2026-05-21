@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Rutas de Recolección</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB04 — Gestión de rutas y asignación de órdenes</p>

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
            <span class="text-sm font-semibold text-white">{{ $rutas->count() }} rutas</span>
            <button onclick="openNewForm()"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110"
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Nueva Ruta
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Chofer</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Vehículo</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Hora Salida</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Carga</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Fecha</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Órdenes</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rutas as $ruta)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">RT-{{ str_pad($ruta->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-white">{{ $ruta->chofer->nombre }}</td>
                            <td class="px-4 py-3 text-white">{{ $ruta->vehiculo->placa }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $ruta->hora_salida }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $ruta->carga_estimada ?? '—' }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $ruta->fecha->format('Y-m-d') }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $ruta->ordenesServicio->count() }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $estadoStyle = match($ruta->estado) {
                                        'Completada' => 'background:#065f4622;color:#34d399;border:1px solid #065f46;',
                                        'En Progreso' => 'background:#1e3a5f22;color:#93c5fd;border:1px solid #1e3a5f;',
                                        'Pendiente' => 'background:#f59e0b22;color:#f59e0b;border:1px solid #f59e0b;',
                                        'Cancelada' => 'background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;',
                                        default => 'background:#33415522;color:#94a3b8;border:1px solid #334155;',
                                    };
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium"
                                      style="{{ $estadoStyle }}">
                                    {{ $ruta->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $ruta->id }})"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110"
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('rutas.destroy', $ruta) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Está seguro de eliminar esta ruta?');"
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
                            <td colspan="9" class="px-4 py-8 text-center" style="color:#64748b;">
                                No hay rutas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Ruta -->
    <div id="new-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Nueva Ruta de Recolección</h3>
            <form action="{{ route('rutas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Chofer</label>
                    <select name="chofer_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione chofer</option>
                        @foreach($choferes as $chofer)
                            <option value="{{ $chofer->id }}">{{ $chofer->nombre }}</option>
                        @endforeach
                    </select>
                    @error('chofer_id')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Vehículo</label>
                    <select name="vehiculo_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione vehículo</option>
                        @foreach($vehiculos as $vehiculo)
                            <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }} — {{ $vehiculo->tipo }}</option>
                        @endforeach
                    </select>
                    @error('vehiculo_id')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Hora de Salida</label>
                    <input name="hora_salida"
                           type="time"
                           required
                           value="{{ old('hora_salida', '08:00') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                    @error('hora_salida')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Carga Estimada</label>
                    <input name="carga_estimada"
                           value="{{ old('carga_estimada') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Ej. 5.5 Ton">
                    <p id="vehiculo-capacidad-indicator" class="mt-1 text-xs" style="color:#64748b;"></p>
                    @error('carga_estimada')
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

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Órdenes de Servicio</label>
                    <div class="max-h-32 overflow-y-auto rounded-lg p-2 space-y-1" style="background:#0f172a;border:1px solid #334155;">
                        @forelse($ordenesDisponibles as $orden)
                            <label class="flex items-center gap-2 text-sm text-white cursor-pointer">
                                <input type="checkbox" name="ordenes_servicio_ids[]" value="{{ $orden->id }}"
                                       class="rounded" style="accent-color:#f59e0b;">
                                <span class="mono" style="color:#f59e0b;">OS-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span>{{ $orden->cliente->nombre }}</span>
                            </label>
                        @empty
                            <span class="text-xs" style="color:#64748b;">No hay órdenes disponibles</span>
                        @endforelse
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110"
                            style="background:#f59e0b;color:#0f172a;">
                        Crear Ruta
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

    <!-- Modal Overlay for Edit Ruta -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Ruta</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Chofer</label>
                    <select name="chofer_id"
                            id="edit-chofer_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($choferes as $chofer)
                            <option value="{{ $chofer->id }}">{{ $chofer->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Vehículo</label>
                    <select name="vehiculo_id"
                            id="edit-vehiculo_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($vehiculos as $vehiculo)
                            <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }} — {{ $vehiculo->tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Hora de Salida</label>
                    <input name="hora_salida"
                           id="edit-hora_salida"
                           type="time"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Carga Estimada</label>
                    <input name="carga_estimada"
                           id="edit-carga_estimada"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                    <p id="edit-vehiculo-capacidad-indicator" class="mt-1 text-xs" style="color:#64748b;"></p>
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
                        <option value="En Progreso">En Progreso</option>
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
    const rutas = @json($rutas);
    const vehiculos = @json($vehiculos);

    function getCapacidadText(vehiculoId) {
        const v = vehiculos.find(v => v.id === vehiculoId);
        return v ? `Capacidad máxima del vehículo: ${v.capacidad}` : '';
    }

    function updateCapacidadIndicator(selectId, indicatorId) {
        const select = document.getElementById(selectId);
        const indicator = document.getElementById(indicatorId);
        if (!select || !indicator) return;
        const text = getCapacidadText(parseInt(select.value));
        indicator.textContent = text;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const newSelect = document.querySelector('select[name="vehiculo_id"]');
        if (newSelect) {
            newSelect.addEventListener('change', function () {
                updateCapacidadIndicator('vehiculo_id', 'vehiculo-capacidad-indicator');
            });
            updateCapacidadIndicator('vehiculo_id', 'vehiculo-capacidad-indicator');
        }

        const editSelect = document.getElementById('edit-vehiculo_id');
        if (editSelect) {
            editSelect.addEventListener('change', function () {
                updateCapacidadIndicator('edit-vehiculo_id', 'edit-vehiculo-capacidad-indicator');
            });
        }
    });

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
        setTimeout(() => updateCapacidadIndicator('vehiculo_id', 'vehiculo-capacidad-indicator'), 50);
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const ruta = rutas.find(r => r.id === id);
        if (!ruta) return;

        document.getElementById('edit-chofer_id').value = ruta.chofer_id;
        document.getElementById('edit-vehiculo_id').value = ruta.vehiculo_id;
        document.getElementById('edit-hora_salida').value = ruta.hora_salida;
        document.getElementById('edit-carga_estimada').value = ruta.carga_estimada ?? '';
        document.getElementById('edit-fecha').value = ruta.fecha;
        document.getElementById('edit-estado').value = ruta.estado;

        document.getElementById('edit-form').action = `/rutas/${id}`;
        setTimeout(() => updateCapacidadIndicator('edit-vehiculo_id', 'edit-vehiculo-capacidad-indicator'), 50);
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
