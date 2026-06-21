<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'ruc',
        'contacto',
        'estado',
    ];

    /**
     * Scope a query to only include active clients.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
}
