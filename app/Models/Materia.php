<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Modelo para el catalogo de materias
class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = [
        'codigo',
        'nombre',
        'horas_semanales',
        'tipo_aula',
    ];

    // Profesores que estan capacitados para dictar esta materia
    public function profesores(): BelongsToMany
    {
        return $this->belongsToMany(Profesor::class, 'profesor_materia')
                    ->withTimestamps();
    }

    // Paralelos que se han abierto para esta materia
    public function paralelos(): HasMany
    {
        return $this->hasMany(Paralelo::class);
    }
}
