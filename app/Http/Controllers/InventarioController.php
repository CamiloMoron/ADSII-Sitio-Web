<?php

namespace App\Http\Controllers;

use App\Models\LoteClasificado;
use App\Models\RegistroInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InventarioController extends Controller
{
    public function index()
    {
        $registros = RegistroInventario::with(['lote', 'supervisor'])->orderBy('id', 'asc')->get();

        $lotesPendientes = LoteClasificado::whereDoesntHave('registroInventario')
            ->orderBy('id', 'asc')
            ->get();

        return view('inventario.index', compact('registros', 'lotesPendientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lote_clasificado_id' => ['required', 'exists:lotes_clasificados,id'],
            'peso_bruto' => ['required', 'integer', 'min:0'],
            'peso_final' => ['required', 'integer', 'min:0'],
            'zona_almacen' => ['required', 'string', 'max:255'],
        ], [
            'lote_clasificado_id.required' => 'El lote es obligatorio.',
            'lote_clasificado_id.exists' => 'El lote seleccionado no es válido.',
            'peso_bruto.required' => 'El peso bruto es obligatorio.',
            'peso_final.required' => 'El peso final es obligatorio.',
            'zona_almacen.required' => 'La zona de almacén es obligatoria.',
        ]);

        if ($validated['peso_final'] > $validated['peso_bruto']) {
            return back()->withErrors(['peso_final' => 'El peso final no puede ser mayor al peso bruto.'])->withInput();
        }

        $validated['merma'] = $validated['peso_bruto'] - $validated['peso_final'];
        $validated['estado'] = 'Cerrado';
        $validated['supervisor_id'] = Auth::id();

        RegistroInventario::create($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Lote cerrado y stock actualizado.');
    }

    public function update(Request $request, RegistroInventario $registro)
    {
        $validated = $request->validate([
            'lote_clasificado_id' => ['required', 'exists:lotes_clasificados,id'],
            'peso_bruto' => ['required', 'integer', 'min:0'],
            'peso_final' => ['required', 'integer', 'min:0'],
            'zona_almacen' => ['required', 'string', 'max:255'],
            'estado' => ['required', Rule::in(['Cerrado', 'Abierto'])],
        ], [
            'lote_clasificado_id.required' => 'El lote es obligatorio.',
            'peso_bruto.required' => 'El peso bruto es obligatorio.',
            'peso_final.required' => 'El peso final es obligatorio.',
            'zona_almacen.required' => 'La zona de almacén es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        if ($validated['peso_final'] > $validated['peso_bruto']) {
            return back()->withErrors(['peso_final' => 'El peso final no puede ser mayor al peso bruto.'])->withInput();
        }

        $validated['merma'] = $validated['peso_bruto'] - $validated['peso_final'];

        $registro->update($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Registro de inventario actualizado.');
    }

    public function destroy(RegistroInventario $registro)
    {
        $registro->delete();

        return redirect()->route('inventario.index')
            ->with('success', 'Registro de inventario eliminado.');
    }
}
