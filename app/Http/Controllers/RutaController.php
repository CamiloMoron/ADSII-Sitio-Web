<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use App\Models\Ruta;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RutaController extends Controller
{
    public function index()
    {
        $rutas = Ruta::with(['chofer', 'vehiculo', 'ordenesServicio.cliente'])
            ->orderBy('created_at', 'desc')
            ->get();
        $choferes = User::activos()->whereHas('role', fn ($q) => $q->where('name', 'Chofer'))->orderBy('nombre')->get();
        $vehiculos = Vehiculo::operativos()->orderBy('placa')->get();
        $ordenesDisponibles = OrdenServicio::activas()->orderBy('fecha')->get();
        return view('rutas.index', compact('rutas', 'choferes', 'vehiculos', 'ordenesDisponibles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chofer_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $isChofer = User::where('id', $value)
                        ->whereHas('role', fn ($q) => $q->where('name', 'Chofer'))
                        ->exists();
                    if (!$isChofer) {
                        $fail('El usuario seleccionado no tiene el rol de Chofer.');
                    }
                },
            ],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'hora_salida' => ['required', 'date_format:H:i'],
            'carga_estimada' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $vehiculo = Vehiculo::find($request->vehiculo_id);
                    if (!$vehiculo) return;

                    $numericValue = (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION);
                    $capacidadNumeric = (float) filter_var($vehiculo->capacidad, FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION);

                    if ($numericValue > $capacidadNumeric) {
                        $fail("La carga estimada ({$numericValue}) supera la capacidad máxima del vehículo ({$vehiculo->capacidad}).");
                    }
                },
            ],
            'fecha' => ['required', 'date'],
        ], [
            'chofer_id.required' => 'El chofer es obligatorio.',
            'chofer_id.exists' => 'El chofer seleccionado no es válido.',
            'vehiculo_id.required' => 'El vehículo es obligatorio.',
            'vehiculo_id.exists' => 'El vehículo seleccionado no es válido.',
            'hora_salida.required' => 'La hora de salida es obligatoria.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $validated['estado'] = 'Pendiente';

        $ruta = Ruta::create($validated);

        if ($request->filled('ordenes_servicio_ids')) {
            $ordenServicioIds = collect($request->ordenes_servicio_ids)
                ->mapWithKeys(function ($id, $index) {
                    return [$id => ['orden_recorrido' => $index + 1]];
                });
            $ruta->ordenesServicio()->sync($ordenServicioIds);
        }

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta creada exitosamente.');
    }

    public function update(Request $request, Ruta $ruta)
    {
        $validated = $request->validate([
            'chofer_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $isChofer = User::where('id', $value)
                        ->whereHas('role', fn ($q) => $q->where('name', 'Chofer'))
                        ->exists();
                    if (!$isChofer) {
                        $fail('El usuario seleccionado no tiene el rol de Chofer.');
                    }
                },
            ],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'hora_salida' => ['required', 'date_format:H:i'],
            'carga_estimada' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $ruta) {
                    $vehiculoId = $request->vehiculo_id ?? $ruta->vehiculo_id;
                    $vehiculo = Vehiculo::find($vehiculoId);
                    if (!$vehiculo) return;

                    $numericValue = (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION);
                    $capacidadNumeric = (float) filter_var($vehiculo->capacidad, FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION);

                    if ($numericValue > $capacidadNumeric) {
                        $fail("La carga estimada ({$numericValue}) supera la capacidad máxima del vehículo ({$vehiculo->capacidad}).");
                    }
                },
            ],
            'estado' => ['required', Rule::in(['Pendiente', 'En Progreso', 'Completada', 'Cancelada'])],
            'fecha' => ['required', 'date'],
        ], [
            'chofer_id.required' => 'El chofer es obligatorio.',
            'chofer_id.exists' => 'El chofer seleccionado no es válido.',
            'vehiculo_id.required' => 'El vehículo es obligatorio.',
            'vehiculo_id.exists' => 'El vehículo seleccionado no es válido.',
            'hora_salida.required' => 'La hora de salida es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $ruta->update($validated);

        if ($request->has('ordenes_servicio_ids')) {
            $ordenServicioIds = collect($request->ordenes_servicio_ids ?? [])
                ->mapWithKeys(function ($id, $index) {
                    return [$id => ['orden_recorrido' => $index + 1]];
                });
            $ruta->ordenesServicio()->sync($ordenServicioIds);
        }

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta actualizada exitosamente.');
    }

    public function destroy(Ruta $ruta)
    {
        $ruta->ordenesServicio()->detach();
        $ruta->delete();

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta eliminada exitosamente.');
    }
}
