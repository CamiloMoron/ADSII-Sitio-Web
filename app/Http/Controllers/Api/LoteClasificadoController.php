<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoteClasificado;
use Illuminate\Http\Request;

class LoteClasificadoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'material_sugerido' => ['required', 'string', 'max:255'],
            'confianza' => ['required', 'string'],
            'operario_id' => ['required', 'exists:users,id'],
        ]);

        $path = $request->file('foto')->store('lotes', 'public');

        $lote = LoteClasificado::create([
            'material_sugerido' => $validated['material_sugerido'],
            'confianza' => $validated['confianza'],
            'validacion' => 'Confirmada',
            'material_final' => $validated['material_sugerido'],
            'operario_id' => $validated['operario_id'],
            'foto_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clasificación registrada con éxito',
            'data' => $lote,
        ], 201);
    }
}
