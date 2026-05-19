<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OrdenServicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrdenServicioController extends Controller
{
    public function index()
    {
        $ordenes = OrdenServicio::with('cliente')->orderBy('created_at', 'desc')->get();
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        return view('ordenes-servicio.index', compact('ordenes', 'clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'sede' => ['required', 'string', 'max:255'],
            'ventana_horaria' => ['required', 'string', 'max:255'],
            'volumen_estimado' => ['required', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
        ], [
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            'sede.required' => 'La sede es obligatoria.',
            'ventana_horaria.required' => 'La ventana horaria es obligatoria.',
            'volumen_estimado.required' => 'El volumen estimado es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $validated['estado'] = 'Pendiente';

        OrdenServicio::create($validated);

        return redirect()->route('ordenes-servicio.index')
            ->with('success', 'Orden de Servicio creada exitosamente.');
    }

    public function update(Request $request, OrdenServicio $orden_servicio)
    {
        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'sede' => ['required', 'string', 'max:255'],
            'ventana_horaria' => ['required', 'string', 'max:255'],
            'volumen_estimado' => ['required', 'string', 'max:255'],
            'estado' => ['required', Rule::in(['Pendiente', 'Aprobada', 'En Ruta', 'Completada', 'Cancelada'])],
            'fecha' => ['required', 'date'],
        ], [
            'cliente_id.required' => 'El cliente es obligatorio.',
            'sede.required' => 'La sede es obligatoria.',
            'ventana_horaria.required' => 'La ventana horaria es obligatoria.',
            'volumen_estimado.required' => 'El volumen estimado es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $orden_servicio->update($validated);

        return redirect()->route('ordenes-servicio.index')
            ->with('success', 'Orden de Servicio actualizada exitosamente.');
    }

    public function destroy(OrdenServicio $orden_servicio)
    {
        $orden_servicio->delete();

        return redirect()->route('ordenes-servicio.index')
            ->with('success', 'Orden de Servicio eliminada exitosamente.');
    }
}
