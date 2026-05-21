@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Guías de Recojo</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB08 — Registro de pesaje y evidencias en campo</p>

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
            <span class="text-sm font-semibold text-white">{{ $guias->count() }} guías</span>
            <button onclick="openNewForm()"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110"
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Nueva Guía
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Ruta</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Cliente</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Peso</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Firma</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Foto</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guias as $guia)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">GR-{{ str_pad($guia->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 mono text-white">RT-{{ str_pad($guia->ruta->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-white">
                                @if($guia->ruta->ordenesServicio->isNotEmpty())
                                    {{ $guia->ruta->ordenesServicio->first()->cliente->nombre }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $guia->peso_kg ? number_format($guia->peso_kg, 0) . ' Kg' : '—' }}</td>
                            <td class="px-4 py-3">
                                <span style="color:{{ $guia->firma ? '#34d399' : '#fca5a5' }};">
                                    <i data-lucide="{{ $guia->firma ? 'check' : 'x' }}" style="width:16px;height:16px;"></i>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span style="color:{{ $guia->foto ? '#34d399' : '#fca5a5' }};">
                                    <i data-lucide="{{ $guia->foto ? 'check' : 'x' }}" style="width:16px;height:16px;"></i>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $estadoStyle = match($guia->estado) {
                                        'Completada' => 'background:#065f4622;color:#34d399;border:1px solid #065f46;',
                                        'En Proceso' => 'background:#f59e0b22;color:#f59e0b;border:1px solid #f59e0b;',
                                        default => 'background:#33415522;color:#94a3b8;border:1px solid #334155;',
                                    };
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium"
                                      style="{{ $estadoStyle }}">
                                    {{ $guia->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $guia->id }})"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110"
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('guias.destroy', $guia) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Está seguro de eliminar esta guía?');"
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
                                No hay guías de recojo registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Guia -->
    <div id="new-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Nueva Guía de Recojo</h3>
            <form action="{{ route('guias.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Ruta</label>
                    <select name="ruta_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione ruta</option>
                        @foreach($rutas as $ruta)
                            <option value="{{ $ruta->id }}">R-{{ str_pad($ruta->id, 4, '0', STR_PAD_LEFT) }} - {{ $ruta->chofer->nombre }} ({{ $ruta->vehiculo->placa }})</option>
                        @endforeach
                    </select>
                    @error('ruta_id')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Peso (Kg)</label>
                    <input name="peso_kg"
                           type="number"
                           step="0.01"
                           min="0"
                           value="{{ old('peso_kg') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Ej. 1850">
                    @error('peso_kg')
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

                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="firma" value="1" {{ old('firma') ? 'checked' : '' }}
                               class="rounded" style="accent-color:#f59e0b;">
                        <span class="text-sm text-white">Firma del cliente</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="foto" value="1" {{ old('foto') ? 'checked' : '' }}
                               class="rounded" style="accent-color:#f59e0b;">
                        <span class="text-sm text-white">Foto de evidencia</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110"
                            style="background:#f59e0b;color:#0f172a;">
                        Crear Guía
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

    <!-- Modal Overlay for Edit Guia -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Guía de Recojo</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Ruta</label>
                    <select name="ruta_id"
                            id="edit-ruta_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($rutas as $ruta)
                            <option value="{{ $ruta->id }}">R-{{ str_pad($ruta->id, 4, '0', STR_PAD_LEFT) }} - {{ $ruta->chofer->nombre }} ({{ $ruta->vehiculo->placa }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Peso (Kg)</label>
                    <input name="peso_kg"
                           id="edit-peso_kg"
                           type="number"
                           step="0.01"
                           min="0"
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

                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="firma" value="1" id="edit-firma"
                               class="rounded" style="accent-color:#f59e0b;">
                        <span class="text-sm text-white">Firma del cliente</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="foto" value="1" id="edit-foto"
                               class="rounded" style="accent-color:#f59e0b;">
                        <span class="text-sm text-white">Foto de evidencia</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Estado</label>
                    <select name="estado"
                            id="edit-estado"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="En Proceso">En Proceso</option>
                        <option value="Completada">Completada</option>
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
    const guias = @json($guias);

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const guia = guias.find(g => g.id === id);
        if (!guia) return;

        document.getElementById('edit-ruta_id').value = guia.ruta_id;
        document.getElementById('edit-peso_kg').value = guia.peso_kg ?? '';
        document.getElementById('edit-fecha').value = guia.fecha;
        document.getElementById('edit-firma').checked = guia.firma;
        document.getElementById('edit-foto').checked = guia.foto;
        document.getElementById('edit-estado').value = guia.estado;

        document.getElementById('edit-form').action = `/guias/${id}`;
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
