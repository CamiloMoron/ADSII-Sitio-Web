<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\OrdenVenta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::with('ordenVenta')->orderBy('id', 'asc')->get();
        $ordenesVenta = OrdenVenta::orderBy('cliente')->get();
        return view('facturas.index', compact('facturas', 'ordenesVenta'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'orden_venta_id' => ['required', 'exists:orden_ventas,id'],
            'ruc_cliente' => ['required', 'regex:/^\d{11}$/'],
            'fecha_emision' => ['required', 'date'],
        ], [
            'orden_venta_id.required' => 'La orden de venta es obligatoria.',
            'orden_venta_id.exists' => 'La orden de venta seleccionada no es válida.',
            'ruc_cliente.required' => 'El RUC del cliente es obligatorio.',
            'ruc_cliente.regex' => 'El RUC debe tener exactamente 11 dígitos.',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
        ]);

        $ordenVenta = OrdenVenta::findOrFail($validated['orden_venta_id']);

        $validated['subtotal'] = $ordenVenta->subtotal;
        $validated['igv'] = $ordenVenta->igv;
        $validated['total'] = $ordenVenta->total;
        $validated['estado'] = 'Emitida';

        $nextId = Factura::max('id') + 1;
        $validated['numero_factura'] = 'F001-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        Factura::create($validated);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura electrónica registrada exitosamente.');
    }

    public function update(Request $request, Factura $factura)
    {
        $validated = $request->validate([
            'orden_venta_id' => ['required', 'exists:orden_ventas,id'],
            'ruc_cliente' => ['required', 'regex:/^\d{11}$/'],
            'fecha_emision' => ['required', 'date'],
            'estado' => ['required', Rule::in(['Emitida', 'Pagada', 'Anulada'])],
            'cdr' => ['nullable', 'string', 'max:255'],
        ], [
            'orden_venta_id.required' => 'La orden de venta es obligatoria.',
            'ruc_cliente.required' => 'El RUC del cliente es obligatorio.',
            'ruc_cliente.regex' => 'El RUC debe tener exactamente 11 dígitos.',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $ordenVenta = OrdenVenta::findOrFail($validated['orden_venta_id']);
        $validated['subtotal'] = $ordenVenta->subtotal;
        $validated['igv'] = $ordenVenta->igv;
        $validated['total'] = $ordenVenta->total;

        $factura->update($validated);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura electrónica actualizada exitosamente.');
    }

    public function destroy(Factura $factura)
    {
        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura electrónica eliminada exitosamente.');
    }
}
