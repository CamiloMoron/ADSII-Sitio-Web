@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Órdenes de Venta</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB05 — Registro de transacciones comerciales</p>

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
                Nueva Venta
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Cliente</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Material</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Cantidad</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Total</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Fecha</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenes as $orden)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">OV-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-white">{{ $orden->cliente }}</td>
                            <td class="px-4 py-3 text-white">{{ $orden->material }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $orden->cantidad }}</td>
                            <td class="px-4 py-3 font-semibold text-white">S/ {{ number_format($orden->total, 2) }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $orden->fecha->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $estadoStyle = match($orden->estado) {
                                        'Facturada' => 'background:#065f4622;color:#34d399;border:1px solid #065f46;',
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
                                    <form action="{{ route('ordenes-venta.destroy', $orden) }}"
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
                                No hay órdenes de venta registradas
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
            <h3 class="text-lg font-bold text-white mb-4">Nueva Orden de Venta</h3>
            <form action="{{ route('ordenes-venta.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Comprador</label>
                    <input name="cliente"
                           required
                           value="{{ old('cliente') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Nombre del comprador">
                    @error('cliente')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Material</label>
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
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Cantidad</label>
                    <input name="cantidad"
                           required
                           value="{{ old('cantidad') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Ej. 500 Kg">
                    @error('cantidad')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Precio Unitario (S/)</label>
                    <input name="precio_unitario"
                           id="new-precio"
                           type="number"
                           required
                           value="{{ old('precio_unitario') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="0.00"
                           step="0.01">
                    @error('precio_unitario')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-3 rounded-lg" style="background:#0f172a;border:1px solid #334155;">
                    <div class="flex justify-between mb-2">
                        <span style="color:#94a3b8;">Subtotal:</span>
                        <span class="text-white" id="new-subtotal">S/ 0.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span style="color:#94a3b8;">IGV (18%):</span>
                        <span class="text-white" id="new-igv">S/ 0.00</span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span class="text-white">Total:</span>
                        <span class="text-white" id="new-total">S/ 0.00</span>
                    </div>
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
                        Crear Venta
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
            <h3 class="text-lg font-bold text-white mb-4">Editar Orden de Venta</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Comprador</label>
                    <input name="cliente"
                           id="edit-cliente"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

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
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Cantidad</label>
                    <input name="cantidad"
                           id="edit-cantidad"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Precio Unitario (S/)</label>
                    <input name="precio_unitario"
                           id="edit-precio"
                           type="number"
                           required
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           step="0.01">
                </div>

                <div class="p-3 rounded-lg" style="background:#0f172a;border:1px solid #334155;">
                    <div class="flex justify-between mb-2">
                        <span style="color:#94a3b8;">Subtotal:</span>
                        <span class="text-white" id="edit-subtotal">S/ 0.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span style="color:#94a3b8;">IGV (18%):</span>
                        <span class="text-white" id="edit-igv">S/ 0.00</span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span class="text-white">Total:</span>
                        <span class="text-white" id="edit-total">S/ 0.00</span>
                    </div>
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
                        <option value="Facturada">Facturada</option>
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

    function calcSubtotalIgvTotal(precio) {
        const subtotal = precio;
        const igv = subtotal * 0.18;
        const total = subtotal + igv;
        return { subtotal, igv, total };
    }

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
        document.getElementById('new-subtotal').textContent = 'S/ 0.00';
        document.getElementById('new-igv').textContent = 'S/ 0.00';
        document.getElementById('new-total').textContent = 'S/ 0.00';
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const orden = ordenes.find(o => o.id === id);
        if (!orden) return;

        document.getElementById('edit-cliente').value = orden.cliente;
        document.getElementById('edit-material').value = orden.material;
        document.getElementById('edit-cantidad').value = orden.cantidad;
        document.getElementById('edit-precio').value = orden.precio_unitario;
        document.getElementById('edit-fecha').value = orden.fecha;
        document.getElementById('edit-estado').value = orden.estado;

        const calc = calcSubtotalIgvTotal(parseFloat(orden.precio_unitario) || 0);
        document.getElementById('edit-subtotal').textContent = `S/ ${calc.subtotal.toFixed(2)}`;
        document.getElementById('edit-igv').textContent = `S/ ${calc.igv.toFixed(2)}`;
        document.getElementById('edit-total').textContent = `S/ ${calc.total.toFixed(2)}`;

        document.getElementById('edit-form').action = `/ordenes-venta/${id}`;
        document.getElementById('edit-form-overlay').classList.remove('hidden');
    }

    function closeEditForm() {
        document.getElementById('edit-form-overlay').classList.add('hidden');
    }

    document.getElementById('new-precio')?.addEventListener('input', function () {
        const precio = parseFloat(this.value) || 0;
        const calc = calcSubtotalIgvTotal(precio);
        document.getElementById('new-subtotal').textContent = `S/ ${calc.subtotal.toFixed(2)}`;
        document.getElementById('new-igv').textContent = `S/ ${calc.igv.toFixed(2)}`;
        document.getElementById('new-total').textContent = `S/ ${calc.total.toFixed(2)}`;
    });

    document.getElementById('edit-precio')?.addEventListener('input', function () {
        const precio = parseFloat(this.value) || 0;
        const calc = calcSubtotalIgvTotal(precio);
        document.getElementById('edit-subtotal').textContent = `S/ ${calc.subtotal.toFixed(2)}`;
        document.getElementById('edit-igv').textContent = `S/ ${calc.igv.toFixed(2)}`;
        document.getElementById('edit-total').textContent = `S/ ${calc.total.toFixed(2)}`;
    });

    @if($errors->any() && old('_method') === null)
        openNewForm();
    @endif

    lucide.createIcons();
</script>
@endsection
