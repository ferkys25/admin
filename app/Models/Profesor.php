<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Modelo para la tabla de profesores
class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    // Campos que permitimos rellenar desde los formularios
    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'correo',
    ];

    // Un profesor puede dar muchas materias a traves de la tabla pivote profesor_materia
    public function materias(): BelongsToMany
    {
        return $this->belongsToMany(Materia::class, 'profesor_materia')
                    ->withTimestamps();
    }

    // Un profesor puede tener varios paralelos asignados
    public function paralelos(): HasMany
    {
        return $this->hasMany(Paralelo::class);
    }

    // Funcion auxiliar para juntar apellido y nombre facil en las vistas
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->apellido} {$this->nombre}";
    }
}
