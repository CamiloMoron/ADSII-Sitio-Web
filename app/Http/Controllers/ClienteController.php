<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::orderBy('id', 'asc')->get();
        return view('catalogos.clientes.index', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'ruc' => ['required', 'regex:/^\d{11}$/', 'unique:clientes,ruc'],
            'contacto' => ['required', 'string', 'max:255'],
        ], [
            'ruc.regex' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'Este RUC ya está registrado.',
            'nombre.required' => 'El nombre es obligatorio.',
            'contacto.required' => 'El contacto es obligatorio.',
        ]);

        $validated['estado'] = 'Activo';

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'ruc' => ['required', 'regex:/^\d{11}$/', Rule::unique('clientes')->ignore($cliente->id)],
            'contacto' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'in:Activo,Inactivo'],
        ], [
            'ruc.regex' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'Este RUC ya está registrado.',
            'nombre.required' => 'El nombre es obligatorio.',
            'contacto.required' => 'El contacto es obligatorio.',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}
