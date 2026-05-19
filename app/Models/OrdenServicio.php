<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenServicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'sede',
        'ventana_horaria',
        'volumen_estimado',
        'estado',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function scopeActivas($query)
    {
        return $query->whereNotIn('estado', ['Cancelada', 'Completada']);
    }
}
