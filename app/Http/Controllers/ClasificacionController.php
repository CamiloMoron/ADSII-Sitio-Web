<?php

namespace App\Http\Controllers;

use App\Models\LoteClasificado;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasificacionController extends Controller
{
    public function index()
    {
        $lotes = LoteClasificado::with('operario')->orderBy('id', 'asc')->get();
        $materiales = Material::orderBy('nombre')->get();
        return view('clasificacion.index', compact('lotes', 'materiales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_sugerido' => ['required', 'string', 'max:255'],
            'validacion' => ['required', 'in:Confirmada,Corregida'],
            'material_final' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'material_sugerido.required' => 'La sugerencia IA es obligatoria.',
            'validacion.required' => 'Debe indicar si valida o corrige la sugerencia.',
            'material_final.required' => 'El material final es obligatorio.',
        ]);

        $validated['confianza'] = '95%';
        $validated['operario_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $validated['foto_path'] = $request->file('foto')->store('clasificaciones');
        }

        LoteClasificado::create($validated);

        return redirect()->route('clasificacion.index')
            ->with('success', 'Material clasificado exitosamente.');
    }

    public function update(Request $request, LoteClasificado $clasificacion)
    {
        $validated = $request->validate([
            'material_sugerido' => ['required', 'string', 'max:255'],
            'validacion' => ['required', 'in:Confirmada,Corregida'],
            'material_final' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'material_sugerido.required' => 'La sugerencia IA es obligatoria.',
            'validacion.required' => 'Debe indicar si valida o corrige la sugerencia.',
            'material_final.required' => 'El material final es obligatorio.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto_path'] = $request->file('foto')->store('clasificaciones');
        }

        $clasificacion->update($validated);

        return redirect()->route('clasificacion.index')
            ->with('success', 'Clasificación actualizada exitosamente.');
    }

    public function destroy(LoteClasificado $clasificacion)
    {
        $clasificacion->delete();

        return redirect()->route('clasificacion.index')
            ->with('success', 'Clasificación eliminada exitosamente.');
    }
}
