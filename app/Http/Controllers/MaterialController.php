<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materiales = Material::orderBy('created_at', 'desc')->get();
        return view('catalogos.materiales.index', compact('materiales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'max:20', 'unique:materiales,codigo'],
            'nombre' => ['required', 'max:255'],
            'tipo' => ['required', 'in:Peligroso,No Peligroso'],
            'unidad' => ['required', 'max:50'],
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'nombre.required' => 'El nombre es obligatorio.',
            'tipo.required' => 'El tipo es obligatorio.',
            'unidad.required' => 'La unidad es obligatoria.',
        ]);

        Material::create($validated);

        return redirect()->route('materiales.index')
            ->with('success', 'Material registrado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'max:20', Rule::unique('materiales')->ignore($material->id)],
            'nombre' => ['required', 'max:255'],
            'tipo' => ['required', 'in:Peligroso,No Peligroso'],
            'unidad' => ['required', 'max:50'],
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'nombre.required' => 'El nombre es obligatorio.',
            'tipo.required' => 'El tipo es obligatorio.',
            'unidad.required' => 'La unidad es obligatoria.',
        ]);

        $material->update($validated);

        return redirect()->route('materiales.index')
            ->with('success', 'Material actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('materiales.index')
            ->with('success', 'Material eliminado exitosamente.');
    }
}
