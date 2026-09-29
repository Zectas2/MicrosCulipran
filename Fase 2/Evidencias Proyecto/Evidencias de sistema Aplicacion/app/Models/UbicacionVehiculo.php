<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionVehiculo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ubicaciones_vehiculos';
    public $timestamps = false; 

    protected $fillable = [
        'recorrido_id',
        'latitud',
        'longitud',
        'registrado_en',
    ];

    public function recorrido()
    {
        return $this->belongsTo(Recorrido::class);
    }
}