@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Gestión de Materiales</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB02 — Catálogo de materiales</p>

    @include('catalogos._tabs')

    <!-- Success Message -->
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
            <span class="text-sm font-semibold text-white">{{ $materiales->count() }} registros</span>
            <button onclick="openNewForm()" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110" 
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Nuevo Material
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Código</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Nombre</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Tipo</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Unidad</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materiales as $material)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 text-white">{{ $material->id }}</td>
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">{{ $material->codigo }}</td>
                            <td class="px-4 py-3 text-white">{{ $material->nombre }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium" 
                                      style="{{ $material->tipo === 'Peligroso' ? 'background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;' : 'background:#1e3a5f22;color:#93c5fd;border:1px solid #1e3a5f;' }}">
                                    {{ $material->tipo }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-white">{{ $material->unidad }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $material->id }})" 
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110" 
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('materiales.destroy', $material) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Está seguro de eliminar este material?');"
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
                                No hay materiales registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Material -->
    <div id="new-form-overlay" class="hidden fixed inset-0 z-40 flex items-center justify-center" style="background:rgba(0,0,0,0.6);">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Nuevo Material</h3>
            <form action="{{ route('materiales.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Código</label>
                    <input name="codigo" 
                           required 
                           value="{{ old('codigo') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. RES-001">
                    @error('codigo')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Nombre</label>
                    <input name="nombre" 
                           required 
                           value="{{ old('nombre') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. Aceite usado">
                    @error('nombre')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Tipo</label>
                    <select name="tipo" 
                            required 
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione tipo</option>
                        <option value="Peligroso">Peligroso</option>
                        <option value="No Peligroso">No Peligroso</option>
                    </select>
                    @error('tipo')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Unidad</label>
                    <input name="unidad" 
                           required 
                           value="{{ old('unidad') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. Galón">
                    @error('unidad')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" 
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110" 
                            style="background:#f59e0b;color:#0f172a;">
                        Guardar
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

    <!-- Modal Overlay for Edit Material -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 z-40 flex items-center justify-center" style="background:rgba(0,0,0,0.6);">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Material</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Código</label>
                    <input name="codigo" 
                           id="edit-codigo"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Nombre</label>
                    <input name="nombre" 
                           id="edit-nombre"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Tipo</label>
                    <select name="tipo" 
                            id="edit-tipo"
                            required 
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Peligroso">Peligroso</option>
                        <option value="No Peligroso">No Peligroso</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Unidad</label>
                    <input name="unidad" 
                           id="edit-unidad"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
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
    // Material data for edit modal
    const materiales = @json($materiales);

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const material = materiales.find(m => m.id === id);
        if (!material) return;

        document.getElementById('edit-codigo').value = material.codigo;
        document.getElementById('edit-nombre').value = material.nombre;
        document.getElementById('edit-tipo').value = material.tipo;
        document.getElementById('edit-unidad').value = material.unidad;
        
        document.getElementById('edit-form').action = `/materiales/${id}`;
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
