<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifaTramo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tarifas_tramos';

    protected $fillable = [
        'ruta_id',
        'punto_origen',
        'punto_destino',
        'precio',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }
}