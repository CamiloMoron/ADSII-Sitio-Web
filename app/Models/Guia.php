<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guia extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruta_id',
        'peso_kg',
        'firma',
        'foto',
        'estado',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
        'firma' => 'boolean',
        'foto' => 'boolean',
        'peso_kg' => 'decimal:2',
    ];

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class);
    }
}
