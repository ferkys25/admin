<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Modelo para los paralelos o grupos abiertos
class Paralelo extends Model
{
    use HasFactory;

    protected $table = 'paralelos';

    protected $fillable = [
        'materia_id',
        'periodo_id',
        'profesor_id',
        'numero',
    ];

    // Materia a la que pertenece este paralelo
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    // Periodo academico en el que se abre
    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    // Profesor asignado a este paralelo
    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class);
    }
}
