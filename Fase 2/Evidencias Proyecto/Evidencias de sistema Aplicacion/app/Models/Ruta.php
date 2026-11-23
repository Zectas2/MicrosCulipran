<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'rutas';

    protected $fillable = [
        'nombre_ruta',
        'origen',
        'destino',
        'trayecto_coordenadas',
    ];

    protected function casts(): array
    {
        return [
            'trayecto_coordenadas' => 'array',
        ];
    }

    public function tarifas()
    {
        return $this->hasMany(TarifaTramo::class);
    }

    public function recorridos()
    {
        return $this->hasMany(Recorrido::class);
    }
}