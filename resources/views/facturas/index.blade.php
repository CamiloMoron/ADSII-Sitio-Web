@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Facturas Electrónicas</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB06 — Emisión de comprobantes ante SUNAT</p>

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
            <span class="text-sm font-semibold text-white">{{ $facturas->count() }} registros</span>
            <button onclick="openNewForm()"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110"
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Nueva Factura
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">OV Origen</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Cliente</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">RUC</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Monto</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Emisión</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">CDR</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">FC-{{ str_pad($factura->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 mono text-white">OV-{{ str_pad($factura->ordenVenta->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-white">{{ $factura->ordenVenta->cliente }}</td>
                            <td class="px-4 py-3 mono" style="color:#94a3b8;">{{ $factura->ruc_cliente }}</td>
                            <td class="px-4 py-3 font-semibold text-white">S/ {{ number_format($factura->total, 2) }}</td>
                            <td class="px-4 py-3" style="color:#94a3b8;">{{ $factura->fecha_emision->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $estadoStyle = match($factura->estado) {
                                        'Emitida', 'Pagada' => 'background:#065f4622;color:#34d399;border:1px solid #065f46;',
                                        'Anulada' => 'background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;',
                                        default => 'background:#33415522;color:#94a3b8;border:1px solid #334155;',
                                    };
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium"
                                      style="{{ $estadoStyle }}">
                                    {{ $factura->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3 mono" style="color:#94a3b8;">{{ $factura->cdr ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $factura->id }})"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110"
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('facturas.destroy', $factura) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Está seguro de eliminar esta factura?');"
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
                                No hay facturas electrónicas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Factura -->
    <div id="new-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Nueva Factura Electrónica</h3>
            <form action="{{ route('facturas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Orden de Venta Origen</label>
                    <select name="orden_venta_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione orden de venta</option>
                        @foreach($ordenesVenta as $ov)
                            <option value="{{ $ov->id }}">OV-{{ str_pad($ov->id, 4, '0', STR_PAD_LEFT) }} — {{ $ov->cliente }} (S/ {{ number_format($ov->total, 2) }})</option>
                        @endforeach
                    </select>
                    @error('orden_venta_id')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">RUC del Cliente (11 dígitos)</label>
                    <input name="ruc_cliente"
                           required
                           maxlength="11"
                           pattern="\d{11}"
                           value="{{ old('ruc_cliente') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="20123456789">
                    @error('ruc_cliente')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Fecha de Emisión</label>
                    <input name="fecha_emision"
                           type="date"
                           required
                           value="{{ old('fecha_emision', date('Y-m-d')) }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                    @error('fecha_emision')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110"
                            style="background:#f59e0b;color:#0f172a;">
                        Emitir Factura
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

    <!-- Modal Overlay for Edit Factura -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 min-h-screen w-screen z-50 flex items-center justify-center p-4 bg-black/60">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in max-h-[85vh] overflow-y-auto" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Factura Electrónica</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Orden de Venta Origen</label>
                    <select name="orden_venta_id"
                            id="edit-orden_venta_id"
                            required
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2"
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($ordenesVenta as $ov)
                            <option value="{{ $ov->id }}">OV-{{ str_pad($ov->id, 4, '0', STR_PAD_LEFT) }} — {{ $ov->cliente }} (S/ {{ number_format($ov->total, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">RUC del Cliente (11 dígitos)</label>
                    <input name="ruc_cliente"
                           id="edit-ruc_cliente"
                           required
                           maxlength="11"
                           pattern="\d{11}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Fecha de Emisión</label>
                    <input name="fecha_emision"
                           id="edit-fecha_emision"
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
                        <option value="Emitida">Emitida</option>
                        <option value="Pagada">Pagada</option>
                        <option value="Anulada">Anulada</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">CDR (Constancia de Recepción)</label>
                    <input name="cdr"
                           id="edit-cdr"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono"
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           placeholder="Ej. CDR-001">
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
    const facturas = @json($facturas);

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const factura = facturas.find(f => f.id === id);
        if (!factura) return;

        document.getElementById('edit-orden_venta_id').value = factura.orden_venta_id;
        document.getElementById('edit-ruc_cliente').value = factura.ruc_cliente;
        document.getElementById('edit-fecha_emision').value = factura.fecha_emision;
        document.getElementById('edit-estado').value = factura.estado;
        document.getElementById('edit-cdr').value = factura.cdr || '';

        document.getElementById('edit-form').action = `/facturas/${id}`;
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
