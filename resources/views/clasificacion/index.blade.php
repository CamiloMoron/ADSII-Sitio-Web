@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Clasificación de Material</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB09 — IA asistida + Validación humana</p>

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
            <span class="text-sm font-semibold text-white">{{ $lotes->count() }} registros</span>
            <button onclick="openNewForm()"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110"
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Clasificar
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Lote</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Material</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">IA Confianza</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Validación</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Operario</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lotes as $lote)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">LOT-{{ str_pad($lote->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-white">{{ $lote->material_final }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium"
                                      style="background:#8b5cf622;color:#d8b4fe;border:1px solid #8b5cf6;">
                                    {{ $lote->confianza }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $valStyle = match($lote->validacion) {
                                        'Confirmada' => 'background:#065f4622;color:#34d399;border:1px solid #065f46;',
                                        'Corregida' => 'background:#f59e0b22;color:#f59e0b;border:1px solid #f59e0b;',
                                    };
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium"
                                      style="{{ $valStyle }}">
                                    {{ $lote->validacion }}
                                </span>
                            </td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $lote->operario->nombre }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $lote->id }})"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110"
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('clasificacion.destroy', $lote) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Está seguro de eliminar esta clasificación?');"
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
                                No hay materiales clasificados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Clasificacion -->
    <div id="new-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Clasificar Material</h3>
            <form action="{{ route('clasificacion.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="p-4 rounded-lg text-center" style="background:#0f172a;border:2px dashed #334155;">
                    <i data-lucide="camera" style="width:32px;height:32px;color:#f59e0b;margin:0 auto 8px;"></i>
                    <p class="text-xs mb-2" style="color:#94a3b8;">Simulación: Escanear residuo con IA</p>
                    <input type="file" name="foto" accept="image/*"
                           class="hidden" id="new-foto-input">
                    <button type="button" onclick="document.getElementById('new-foto-input').click()"
                            class="px-4 py-1.5 rounded-lg text-xs font-medium transition hover:brightness-110"
                            style="background:#334155;color:#94a3b8;">
                        Subir imagen
                    </button>
                    <p id="new-foto-name" class="mt-2 text-xs mono" style="color:#f59e0b;"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Sugerencia IA (95% confianza)</label>
                    <select name="material_sugerido"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#8b5cf6;">
                        @foreach($materiales as $material)
                            <option value="{{ $material->nombre }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>
                    @error('material_sugerido')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">¿Validar sugerencia?</label>
                    <select name="validacion"
                            id="new-validacion"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Confirmada">✓ Confirmada</option>
                        <option value="Corregida">✗ Corregir manualmente</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Material Correcto</label>
                    <select name="material_final"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($materiales as $material)
                            <option value="{{ $material->nombre }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>
                    @error('material_final')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110"
                            style="background:#f59e0b;color:#0f172a;">
                        Guardar Clasificación
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

    <!-- Modal Overlay for Edit Clasificacion -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Clasificación</h3>
            <form id="edit-form" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Sugerencia IA</label>
                    <select name="material_sugerido"
                            id="edit-material_sugerido"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#8b5cf6;">
                        @foreach($materiales as $material)
                            <option value="{{ $material->nombre }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">¿Validar sugerencia?</label>
                    <select name="validacion"
                            id="edit-validacion"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Confirmada">✓ Confirmada</option>
                        <option value="Corregida">✗ Corregir manualmente</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Material Final</label>
                    <select name="material_final"
                            id="edit-material_final"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($materiales as $material)
                            <option value="{{ $material->nombre }}">{{ $material->nombre }}</option>
                        @endforeach
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
    const lotes = @json($lotes);

    document.getElementById('new-foto-input')?.addEventListener('change', function () {
        const name = this.files[0]?.name || '';
        document.getElementById('new-foto-name').textContent = name;
    });

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const lote = lotes.find(l => l.id === id);
        if (!lote) return;

        document.getElementById('edit-material_sugerido').value = lote.material_sugerido;
        document.getElementById('edit-validacion').value = lote.validacion;
        document.getElementById('edit-material_final').value = lote.material_final;

        document.getElementById('edit-form').action = `/clasificacion/${id}`;
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
