<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7fb">
    <title>Inventario | Panel en vivo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-indigo-100/70 via-slate-50 to-transparent"></div>

    <main class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        <header class="mb-8 flex items-center justify-between gap-4">
            <a class="flex items-center gap-3" href="/inventario" aria-label="Panel de inventario">
                <span class="flex size-11 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/20">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9-4 9 4m-18 0 9 4m-9-4v10l9 4m0-10 9-4m-9 4v10m9-14v10l-9 4M7.5 5l9 4" />
                    </svg>
                </span>
                <span>
                    <span class="block text-sm font-bold tracking-tight text-slate-900">Inventario</span>
                    <span class="block text-xs text-slate-500">Panel de control</span>
                </span>
            </a>
            <span id="estado-conexion" class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm">
                <span id="indicador-conexion" class="size-2 rounded-full bg-amber-500"></span>
                <span id="texto-conexion">Conectando...</span>
            </span>
        </header>

        <section class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-950 to-indigo-800 px-6 py-8 text-white shadow-xl shadow-indigo-950/10 sm:px-9 sm:py-10">
            <div class="flex flex-col justify-between gap-8 sm:flex-row sm:items-end">
                <div class="max-w-2xl">
                    <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-indigo-200">Vista general</p>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Inventario en tiempo real</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100/80 sm:text-base">
                        Controla tus productos y observa cada cambio de stock al instante desde todas tus pestañas.
                    </p>
                </div>
                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-white/10 text-indigo-100">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                    <span>
                        <span class="block text-xs text-indigo-100/70">Canal de inventario</span>
                        <span id="detalle-conexion" class="block text-sm font-semibold">Esperando conexión</span>
                    </span>
                </div>
            </div>
        </section>

        <section class="mb-6 grid gap-4 sm:grid-cols-2" aria-label="Resumen del inventario">
            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Productos registrados</p>
                        <p id="total-productos" class="mt-2 text-3xl font-bold tracking-tight text-slate-900">—</p>
                    </div>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Zm0 0V17l9 4 9-4V7.5M12 12v9.5" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs text-slate-400">Referencias disponibles en el catálogo</p>
            </article>
            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Unidades en stock</p>
                        <p id="total-unidades" class="mt-2 text-3xl font-bold tracking-tight text-slate-900">—</p>
                    </div>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 15l3-4 3 2 5-6" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs text-slate-400">Suma de existencias de todos los productos</p>
            </article>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-200/50">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Productos</h2>
                    <p class="mt-1 text-sm text-slate-500">Ajusta el stock y el cambio se reflejará en las demás pestañas.</p>
                </div>
                <span id="ultima-actualizacion" class="inline-flex w-fit items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-xs font-medium text-slate-500">
                    <svg class="size-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Esperando cambios
                </span>
            </div>

            <div id="error" class="mx-5 mt-5 hidden rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 sm:mx-6" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4" scope="col">Producto</th>
                            <th class="px-6 py-4" scope="col">Precio</th>
                            <th class="px-6 py-4" scope="col">Disponibilidad</th>
                            <th class="px-6 py-4 text-right" scope="col">Acción rápida</th>
                        </tr>
                    </thead>
                    <tbody id="productos" class="divide-y divide-slate-100"></tbody>
                </table>
            </div>
        </section>

        <footer class="mt-auto flex flex-col gap-3 pt-6 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <span>Panel de inventario multicanal</span>
            <a href="/inventario" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-2 py-1 font-medium text-indigo-600 transition hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5m-.5-4.5-7 7M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5" />
                </svg>
                Abrir otra pestaña para probar sincronización
            </a>
        </footer>
    </main>

    <script>
        const productosTabla = document.getElementById('productos');
        const error = document.getElementById('error');
        const totalProductos = document.getElementById('total-productos');
        const totalUnidades = document.getElementById('total-unidades');
        const ultimaActualizacion = document.getElementById('ultima-actualizacion');
        const estadoConexion = document.getElementById('estado-conexion');
        const indicadorConexion = document.getElementById('indicador-conexion');
        const textoConexion = document.getElementById('texto-conexion');
        const detalleConexion = document.getElementById('detalle-conexion');
        const formatoPrecio = new Intl.NumberFormat('es-AR', {
            style: 'currency',
            currency: 'ARS',
            maximumFractionDigits: 2,
        });

        function crearCelda(texto, clases = '') {
            const celda = document.createElement('td');
            celda.className = `px-6 py-4 ${clases}`;
            celda.textContent = texto;

            return celda;
        }

        function mostrarError(mensaje) {
            error.textContent = mensaje;
            error.classList.remove('hidden');
        }

        async function cargar() {
            const respuesta = await fetch('/api/productos', {
                headers: { Accept: 'application/json' },
            });

            if (!respuesta.ok) {
                throw new Error(`No se pudo cargar el inventario (${respuesta.status}).`);
            }

            const productos = await respuesta.json();
            const unidades = productos.reduce((total, producto) => total + Number(producto.stock), 0);

            totalProductos.textContent = new Intl.NumberFormat('es-AR').format(productos.length);
            totalUnidades.textContent = new Intl.NumberFormat('es-AR').format(unidades);
            error.textContent = '';
            error.classList.add('hidden');

            if (productos.length === 0) {
                const fila = document.createElement('tr');
                const celda = document.createElement('td');
                celda.colSpan = 4;
                celda.className = 'px-6 py-16 text-center';

                const titulo = document.createElement('p');
                titulo.className = 'font-semibold text-slate-700';
                titulo.textContent = 'Todavía no hay productos';

                const detalle = document.createElement('p');
                detalle.className = 'mt-1 text-sm text-slate-500';
                detalle.textContent = 'Cuando agregues productos, aparecerán aquí.';

                celda.append(titulo, detalle);
                fila.append(celda);
                productosTabla.replaceChildren(fila);

                return;
            }

            const filas = productos.map((producto) => {
                const fila = document.createElement('tr');
                fila.className = 'transition-colors hover:bg-slate-50/80';

                const identidad = document.createElement('td');
                identidad.className = 'px-6 py-4';

                const nombre = document.createElement('p');
                nombre.className = 'font-semibold text-slate-800';
                nombre.textContent = producto.nombre;

                const referencia = document.createElement('p');
                referencia.className = 'mt-1 text-xs text-slate-400';
                referencia.textContent = `ID ${producto.id}`;

                identidad.append(nombre, referencia);
                fila.append(identidad);
                fila.append(crearCelda(formatoPrecio.format(Number(producto.precio)), 'whitespace-nowrap font-medium text-slate-700'));

                const existencia = document.createElement('td');
                existencia.className = 'px-6 py-4';

                const insignia = document.createElement('span');
                const stock = Number(producto.stock);
                insignia.className = stock === 0
                    ? 'inline-flex items-center gap-2 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-200'
                    : stock <= 5
                        ? 'inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200'
                        : 'inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200';
                insignia.textContent = stock === 0 ? 'Sin stock' : `${new Intl.NumberFormat('es-AR').format(stock)} unidades`;
                existencia.append(insignia);
                fila.append(existencia);

                const acciones = document.createElement('td');
                acciones.className = 'px-6 py-4 text-right';

                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:cursor-wait disabled:opacity-60';
                boton.dataset.id = producto.id;
                boton.dataset.stock = producto.stock;
                boton.setAttribute('aria-label', `Aumentar stock de ${producto.nombre}`);
                boton.innerHTML = '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" /></svg><span>Aumentar stock</span>';
                acciones.append(boton);
                fila.append(acciones);

                return fila;
            });

            productosTabla.replaceChildren(...filas);
        }

        document.addEventListener('click', async (evento) => {
            const boton = evento.target.closest('button[data-id]');

            if (!boton) {
                return;
            }

            boton.disabled = true;

            try {
                const respuesta = await fetch(`/api/productos/${boton.dataset.id}`, {
                    method: 'PATCH',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ stock: Number(boton.dataset.stock) + 1 }),
                });

                if (!respuesta.ok) {
                    throw new Error(`No se pudo actualizar el stock (${respuesta.status}).`);
                }

                error.textContent = '';
                error.classList.add('hidden');
                await cargar();
            } catch (fallo) {
                mostrarError(fallo.message);
                boton.disabled = false;
            }
        });

        window.addEventListener('inventario-conexion', (evento) => {
            const conectado = evento.detail === 'connected';
            const noDisponible = ['unavailable', 'failed'].includes(evento.detail);
            const texto = conectado
                ? 'Actualizaciones en vivo'
                : noDisponible
                    ? 'Servidor no disponible'
                    : evento.detail === 'disconnected'
                        ? 'Reconectando...'
                        : 'Conectando...';
            const detalle = conectado
                ? 'Conectado en vivo'
                : noDisponible
                    ? 'Sin conexión en vivo'
                    : 'Esperando conexión';

            estadoConexion.className = conectado
                ? 'inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-sm'
                : 'inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm';
            indicadorConexion.className = conectado
                ? 'size-2 rounded-full bg-emerald-500'
                : 'size-2 rounded-full bg-amber-500';
            textoConexion.textContent = texto;
            detalleConexion.textContent = detalle;
        });

        window.addEventListener('inventario-cambio', (evento) => {
            const { accion, producto } = evento.detail;
            ultimaActualizacion.textContent = `${producto.nombre}: ${accion === 'creado' ? 'producto agregado' : 'stock actualizado'}`;
            cargar().catch((fallo) => {
                mostrarError(fallo.message);
            });
        });

        cargar().catch((fallo) => {
            totalProductos.textContent = '—';
            totalUnidades.textContent = '—';
            mostrarError(fallo.message);
        });
    </script>
</body>
</html>
