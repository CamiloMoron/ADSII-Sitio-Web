<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuiaController extends Controller
{
    public function index()
    {
        $guias = Guia::with(['ruta', 'ruta.chofer', 'ruta.vehiculo', 'ruta.ordenesServicio.cliente'])
            ->orderBy('id', 'asc')
            ->get();
        $rutas = Ruta::with(['chofer', 'vehiculo'])->orderBy('id', 'asc')->get();
        return view('guias.index', compact('guias', 'rutas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruta_id' => ['required', 'exists:rutas,id'],
            'peso_kg' => ['nullable', 'numeric', 'min:0'],
            'firma' => ['nullable', 'boolean'],
            'foto' => ['nullable', 'boolean'],
            'fecha' => ['required', 'date'],
        ], [
            'ruta_id.required' => 'La ruta es obligatoria.',
            'ruta_id.exists' => 'La ruta seleccionada no es válida.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $validated['firma'] = $request->boolean('firma');
        $validated['foto'] = $request->boolean('foto');
        $validated['estado'] = 'En Proceso';

        Guia::create($validated);

        return redirect()->route('guias.index')
            ->with('success', 'Guía de recojo registrada exitosamente.');
    }

    public function update(Request $request, Guia $guia)
    {
        $validated = $request->validate([
            'ruta_id' => ['required', 'exists:rutas,id'],
            'peso_kg' => ['nullable', 'numeric', 'min:0'],
            'firma' => ['nullable', 'boolean'],
            'foto' => ['nullable', 'boolean'],
            'estado' => ['required', Rule::in(['En Proceso', 'Completada'])],
            'fecha' => ['required', 'date'],
        ], [
            'ruta_id.required' => 'La ruta es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $validated['firma'] = $request->boolean('firma');
        $validated['foto'] = $request->boolean('foto');

        $guia->update($validated);

        return redirect()->route('guias.index')
            ->with('success', 'Guía de recojo actualizada exitosamente.');
    }

    public function destroy(Guia $guia)
    {
        $guia->delete();

        return redirect()->route('guias.index')
            ->with('success', 'Guía de recojo eliminada exitosamente.');
    }
}
