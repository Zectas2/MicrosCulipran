import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Detectamos si el sistema se está ejecutando en local o en producción
const esLocal = window.location.hostname === '127.0.0.1' || window.location.hostname === 'localhost';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'irfhn03d72xaqk9bsccf', // Llave pública de Dokploy
    wsHost: esLocal ? '127.0.0.1' : 'ws.microsculipran.duckdns.org',
    wsPort: esLocal ? 8080 : 80,
    wssPort: esLocal ? 8080 : 443,
    forceTLS: !esLocal,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    cluster: 'mt1' // Obligatorio por el estándar interno de la librería
});