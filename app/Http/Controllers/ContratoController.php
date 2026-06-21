<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Cliente;
use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use Illuminate\Database\QueryException;

class ContratoController extends Controller
{
    public function index()
    {
        $contratos = Contrato::with('cliente')->orderBy('id', 'desc')->get();
        return view('contratos.index', compact('contratos'));
    }

    public function create()
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        return view('contratos.create', compact('clientes'));
    }

    public function store(StoreContratoRequest $request)
    {
        Contrato::create($request->validated());

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato registrado exitosamente.');
    }

    public function edit(Contrato $contrato)
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        return view('contratos.edit', compact('contrato', 'clientes'));
    }

    public function update(UpdateContratoRequest $request, Contrato $contrato)
    {
        $contrato->update($request->validated());

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato actualizado exitosamente.');
    }

    public function destroy(Contrato $contrato)
    {
        try {
            $contrato->delete();
            return redirect()->route('contratos.index')
                ->with('success', 'Contrato eliminado exitosamente.');
        } catch (QueryException $e) {
            return redirect()->route('contratos.index')
                ->with('error', 'No se puede eliminar el contrato por restricciones de integridad.');
        }
    }
}
