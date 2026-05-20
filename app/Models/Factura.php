<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_venta_id',
        'numero_factura',
        'ruc_cliente',
        'fecha_emision',
        'subtotal',
        'igv',
        'total',
        'estado',
        'cdr',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_emision' => 'date',
    ];

    public function ordenVenta(): BelongsTo
    {
        return $this->belongsTo(OrdenVenta::class);
    }
}
