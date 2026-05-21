<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoteClasificado extends Model
{
    use HasFactory;

    // Agrega esta línea para forzar el nombre correcto de la tabla en español
    protected $table = 'lotes_clasificados';

    protected $fillable = [
        'material_sugerido',
        'confianza',
        'validacion',
        'material_final',
        'operario_id',
        'foto_path',
    ];

    public function operario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operario_id');
    }
}