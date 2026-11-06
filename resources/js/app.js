//

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

window.Echo.channel('inventario')
    .listen('.producto.actualizado', (evento) => {
        window.dispatchEvent(new CustomEvent('inventario-cambio', { detail: evento }));
    });

const conexion = window.Echo.connector.pusher.connection;

conexion.bind('state_change', ({ current }) => {
    window.dispatchEvent(new CustomEvent('inventario-conexion', { detail: current }));
});

window.dispatchEvent(new CustomEvent('inventario-conexion', { detail: conexion.state }));
