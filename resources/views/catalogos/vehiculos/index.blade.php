@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Gestión de Vehículos</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB02 — Catálogo de vehículos</p>

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
            <span class="text-sm font-semibold text-white">{{ $vehiculos->count() }} registros</span>
            <button onclick="openNewForm()" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110" 
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Nuevo Vehículo
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Placa</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Tipo</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Capacidad</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 text-white">{{ $vehiculo->id }}</td>
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">{{ $vehiculo->placa }}</td>
                            <td class="px-4 py-3 text-white">{{ $vehiculo->tipo }}</td>
                            <td class="px-4 py-3 text-white">{{ $vehiculo->capacidad }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium" 
                                      style="{{ $vehiculo->estado === 'Operativo' ? 'background:#065f4622;color:#34d399;border:1px solid #065f46;' : ($vehiculo->estado === 'En Mantto.' ? 'background:#f59e0b22;color:#f59e0b;border:1px solid #f59e0b;' : 'background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;') }}">
                                    {{ $vehiculo->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $vehiculo->id }})" 
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110" 
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('vehiculos.destroy', $vehiculo) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Está seguro de eliminar este vehículo?');"
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
                                No hay vehículos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Vehículo -->
    <div id="new-form-overlay" class="hidden fixed inset-0 z-40 flex items-center justify-center" style="background:rgba(0,0,0,0.6);">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Nuevo Vehículo</h3>
            <form action="{{ route('vehiculos.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Placa (XXX-999)</label>
                    <input name="placa" 
                           required 
                           value="{{ old('placa') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="ABC-123">
                    @error('placa')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Tipo</label>
                    <input name="tipo" 
                           required 
                           value="{{ old('tipo') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. Camión Baranda">
                    @error('tipo')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Capacidad</label>
                    <input name="capacidad" 
                           required 
                           value="{{ old('capacidad') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. 8 Ton">
                    @error('capacidad')
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

    <!-- Modal Overlay for Edit Vehículo -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 z-40 flex items-center justify-center" style="background:rgba(0,0,0,0.6);">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Vehículo</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Placa (XXX-999)</label>
                    <input name="placa" 
                           id="edit-placa"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Tipo</label>
                    <input name="tipo" 
                           id="edit-tipo"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Capacidad</label>
                    <input name="capacidad" 
                           id="edit-capacidad"
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
                        <option value="Operativo">Operativo</option>
                        <option value="En Mantto.">En Mantto.</option>
                        <option value="Inactivo">Inactivo</option>
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
    // Vehiculo data for edit modal
    const vehiculos = @json($vehiculos);

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const vehiculo = vehiculos.find(v => v.id === id);
        if (!vehiculo) return;

        document.getElementById('edit-placa').value = vehiculo.placa;
        document.getElementById('edit-tipo').value = vehiculo.tipo;
        document.getElementById('edit-capacidad').value = vehiculo.capacidad;
        document.getElementById('edit-estado').value = vehiculo.estado;
        
        document.getElementById('edit-form').action = `/vehiculos/${id}`;
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
