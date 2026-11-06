<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UbicacionTransmitida implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $recorridoId;
    public $vehiculoId;
    public $patente;
    public $latitud;
    public $longitud;
    public $velocidad;
    public $hora;

    public function __construct($recorridoId, $vehiculoId, $patente, $latitud, $longitud, $velocidad = null)
    {
        $this->recorridoId = $recorridoId;
        $this->vehiculoId = $vehiculoId;
        $this->patente = $patente;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->velocidad = $velocidad ?? 0;
        $this->hora = now('America/Santiago')->format('H:i:s');
    }

    public function broadcastOn(): array
    {
        // Canal público: permite a los pasajeros y al administrador escuchar sin requerir login
        return [
            new Channel('flota-vivo'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PosicionActualizada';
    }
}