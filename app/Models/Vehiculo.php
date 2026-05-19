<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehiculos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'placa',
        'tipo',
        'capacidad',
        'estado',
    ];

    /**
     * Scope a query to only include operational vehicles.
     */
    public function scopeOperativos($query)
    {
        return $query->where('estado', 'Operativo');
    }
}
