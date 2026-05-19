<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente',
        'material',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'igv',
        'total',
        'estado',
        'fecha',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha' => 'date',
    ];

    public function scopeActivas($query)
    {
        return $query->whereNotIn('estado', ['Cancelada']);
    }
}
