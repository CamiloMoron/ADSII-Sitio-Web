@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Cierre de Inventario</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB10 — Procesamiento final y actualización de stock</p>

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
            <span class="text-sm font-semibold text-white">{{ $registros->count() }} lotes</span>
            <button onclick="openNewForm()"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110"
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Cerrar Lote
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Lote</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Material</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Peso Final</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Merma</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Zona</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $registro)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">LOT-{{ str_pad($registro->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-white">{{ $registro->material }}</td>
                            <td class="px-4 py-3 font-medium text-white">{{ number_format($registro->peso_final) }} Kg</td>
                            <td class="px-4 py-3" style="color:#fca5a5;">{{ number_format($registro->merma) }} Kg</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $registro->zona_almacen }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $estadoStyle = match($registro->estado) {
                                        'Cerrado' => 'background:#065f4622;color:#34d399;border:1px solid #065f46;',
                                        'Abierto' => 'background:#f59e0b22;color:#f59e0b;border:1px solid #f59e0b;',
                                    };
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium"
                                      style="{{ $estadoStyle }}">
                                    {{ $registro->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $registro->id }})"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110"
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('inventario.destroy', $registro) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Está seguro de eliminar este registro?');"
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
                            <td colspan="7" class="px-4 py-8 text-center" style="color:#64748b;">
                                No hay registros de inventario
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Registro -->
    <div id="new-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Cerrar Lote de Inventario</h3>
            <form action="{{ route('inventario.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Material Procesado</label>
                    <select name="material"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione material</option>
                        @foreach($materiales as $material)
                            <option value="{{ $material->nombre }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>
                    @error('material')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Peso Bruto (Kg)</label>
                    <input name="peso_bruto"
                           id="new-peso_bruto"
                           type="number"
                           required
                           min="0"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="0">
                    @error('peso_bruto')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Peso Final (Kg)</label>
                    <input name="peso_final"
                           id="new-peso_final"
                           type="number"
                           required
                           min="0"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="0">
                    @error('peso_final')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-3 rounded-lg" style="background:#0f172a;">
                    <div class="flex justify-between text-xs" style="color:#94a3b8;">
                        <span>Merma estimada:</span>
                        <span id="new-merma-calc" class="mono" style="color:#fca5a5;">0 Kg</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Zona de Almacén</label>
                    <select name="zona_almacen"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Almacén A">Almacén A</option>
                        <option value="Almacén B">Almacén B</option>
                        <option value="Almacén C">Almacén C</option>
                    </select>
                    @error('zona_almacen')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110"
                            style="background:#f59e0b;color:#0f172a;">
                        Cerrar Lote
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

    <!-- Modal Overlay for Edit Registro -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Registro de Inventario</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Material</label>
                    <select name="material"
                            id="edit-material"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($materiales as $material)
                            <option value="{{ $material->nombre }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Peso Bruto (Kg)</label>
                    <input name="peso_bruto"
                           id="edit-peso_bruto"
                           type="number"
                           required
                           min="0"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Peso Final (Kg)</label>
                    <input name="peso_final"
                           id="edit-peso_final"
                           type="number"
                           required
                           min="0"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div class="p-3 rounded-lg" style="background:#0f172a;">
                    <div class="flex justify-between text-xs" style="color:#94a3b8;">
                        <span>Merma:</span>
                        <span id="edit-merma-calc" class="mono" style="color:#fca5a5;">0 Kg</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Zona de Almacén</label>
                    <select name="zona_almacen"
                            id="edit-zona_almacen"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Almacén A">Almacén A</option>
                        <option value="Almacén B">Almacén B</option>
                        <option value="Almacén C">Almacén C</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Estado</label>
                    <select name="estado"
                            id="edit-estado"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Cerrado">Cerrado</option>
                        <option value="Abierto">Abierto</option>
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
    const registros = @json($registros);

    function calcMerma(brutoId, finalId, calcId) {
        const bruto = parseInt(document.getElementById(brutoId)?.value) || 0;
        const final = parseInt(document.getElementById(finalId)?.value) || 0;
        const el = document.getElementById(calcId);
        if (el) el.textContent = (bruto - final) + ' Kg';
    }

    function bindMermaCalc(brutoId, finalId, calcId) {
        const bruto = document.getElementById(brutoId);
        const final = document.getElementById(finalId);
        if (bruto) bruto.addEventListener('input', () => calcMerma(brutoId, finalId, calcId));
        if (final) final.addEventListener('input', () => calcMerma(brutoId, finalId, calcId));
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindMermaCalc('new-peso_bruto', 'new-peso_final', 'new-merma-calc');
        bindMermaCalc('edit-peso_bruto', 'edit-peso_final', 'edit-merma-calc');
    });

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const r = registros.find(x => x.id === id);
        if (!r) return;

        document.getElementById('edit-material').value = r.material;
        document.getElementById('edit-peso_bruto').value = r.peso_bruto;
        document.getElementById('edit-peso_final').value = r.peso_final;
        document.getElementById('edit-zona_almacen').value = r.zona_almacen;
        document.getElementById('edit-estado').value = r.estado;

        calcMerma('edit-peso_bruto', 'edit-peso_final', 'edit-merma-calc');

        document.getElementById('edit-form').action = `/inventario/${id}`;
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
