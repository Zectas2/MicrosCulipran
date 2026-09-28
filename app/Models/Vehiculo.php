<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'vehiculos';

    protected $fillable = [
        'patente',
        'capacidad',
        'estado',
    ];

    public function recorridos()
    {
        return $this->hasMany(Recorrido::class);
    }
}