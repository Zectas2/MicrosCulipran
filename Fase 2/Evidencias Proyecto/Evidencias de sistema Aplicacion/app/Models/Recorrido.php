<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recorrido extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'recorridos';

    protected $fillable = [
        'ruta_id',
        'vehiculo_id',
        'conductor_id',
        'hora_salida',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'hora_salida' => 'datetime',
        ];
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionVehiculo::class);
    }
}