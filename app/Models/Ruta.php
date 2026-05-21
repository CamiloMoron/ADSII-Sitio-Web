<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ruta extends Model
{
    use HasFactory;

    protected $fillable = [
        'chofer_id',
        'vehiculo_id',
        'hora_salida',
        'carga_estimada',
        'estado',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_salida' => 'datetime:H:i',
    ];

    public function chofer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chofer_id');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function ordenesServicio(): BelongsToMany
    {
        return $this->belongsToMany(OrdenServicio::class, 'orden_servicio_ruta')
            ->withPivot('orden_recorrido')
            ->orderByPivot('orden_recorrido');
    }

    public function scopeActivas($query)
    {
        return $query->whereNotIn('estado', ['Completada', 'Cancelada']);
    }

    public function guia(): HasOne
    {
        return $this->hasOne(Guia::class);
    }
}
