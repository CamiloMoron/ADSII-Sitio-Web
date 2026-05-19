<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'materiales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'unidad',
    ];

    /**
     * Scope a query to only include hazardous materials.
     */
    public function scopePeligrosos($query)
    {
        return $query->where('tipo', 'Peligroso');
    }

    /**
     * Scope a query to only include non-hazardous materials.
     */
    public function scopeNoPeligrosos($query)
    {
        return $query->where('tipo', 'No Peligroso');
    }
}
