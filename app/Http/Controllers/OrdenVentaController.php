<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\OrdenVenta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrdenVentaController extends Controller
{
    public function index()
    {
        $ordenes = OrdenVenta::orderBy('id', 'asc')->get();
        $materiales = Material::orderBy('nombre')->get();
        return view('ordenes-venta.index', compact('ordenes', 'materiales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
            'cantidad' => ['required', 'string', 'max:255'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
            'fecha' => ['required', 'date'],
        ], [
            'cliente.required' => 'El comprador es obligatorio.',
            'material.required' => 'El material es obligatorio.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'precio_unitario.required' => 'El precio unitario es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $precio = (float) $validated['precio_unitario'];
        $validated['subtotal'] = $precio;
        $validated['igv'] = $precio * 0.18;
        $validated['total'] = $precio + ($precio * 0.18);
        $validated['estado'] = 'Pendiente';

        OrdenVenta::create($validated);

        return redirect()->route('ordenes-venta.index')
            ->with('success', 'Orden de Venta registrada exitosamente.');
    }

    public function update(Request $request, OrdenVenta $orden_venta)
    {
        $validated = $request->validate([
            'cliente' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
            'cantidad' => ['required', 'string', 'max:255'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', Rule::in(['Pendiente', 'Facturada', 'Cancelada'])],
            'fecha' => ['required', 'date'],
        ], [
            'cliente.required' => 'El comprador es obligatorio.',
            'material.required' => 'El material es obligatorio.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'precio_unitario.required' => 'El precio unitario es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $precio = (float) $validated['precio_unitario'];
        $validated['subtotal'] = $precio;
        $validated['igv'] = $precio * 0.18;
        $validated['total'] = $precio + ($precio * 0.18);

        $orden_venta->update($validated);

        return redirect()->route('ordenes-venta.index')
            ->with('success', 'Orden de Venta actualizada exitosamente.');
    }

    public function destroy(OrdenVenta $orden_venta)
    {
        $orden_venta->delete();

        return redirect()->route('ordenes-venta.index')
            ->with('success', 'Orden de Venta eliminada exitosamente.');
    }
}
