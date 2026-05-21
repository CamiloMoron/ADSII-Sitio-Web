<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroInventario extends Model
{
    use HasFactory;

    protected $table = 'registros_inventario';

    protected $fillable = [
        'lote_clasificado_id',
        'peso_bruto',
        'peso_final',
        'merma',
        'zona_almacen',
        'estado',
        'supervisor_id',
    ];

    public function lote(): BelongsTo
    {
        return $this->belongsTo(LoteClasificado::class, 'lote_clasificado_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
