import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'irfhn03d72xaqk9bsccf',
    wsHost: 'ws.microsculipran.duckdns.org',
    wsPort: 80,
    wssPort: 443,
    forceTLS: true,
    disableStats: true,
    enabledTransports: ['wss'],
});