const path = require('path');
const grpc = require('@grpc/grpc-js');
const loader = require('@grpc/proto-loader');

const internalKey = process.env.GRPC_INTERNAL_KEY;

if (!internalKey) {
    throw new Error('Debe configurar GRPC_INTERNAL_KEY antes de iniciar el servidor gRPC.');
}

const apiUrl = (process.env.LARAVEL_API_URL || 'http://127.0.0.1:8000').replace(/\/$/, '');
const port = process.env.GRPC_PORT || '50051';
const definition = loader.loadSync(path.join(__dirname, 'producto.proto'));
const api = grpc.loadPackageDefinition(definition).inventario;
const server = new grpc.Server();

server.addService(api.ProductoService.service, {
    async ObtenerProducto(call, callback) {
        const id = Number(call.request.id);

        if (!Number.isSafeInteger(id) || id < 1) {
            callback({ code: grpc.status.INVALID_ARGUMENT, message: 'El id debe ser un entero positivo.' });
            return;
        }

        try {
            const response = await fetch(`${apiUrl}/api/interno/productos/${id}`, {
                headers: { 'X-Internal-Key': internalKey },
            });

            if (response.status === 404) {
                callback({ code: grpc.status.NOT_FOUND, message: 'Producto inexistente.' });
                return;
            }

            if (!response.ok) {
                callback({
                    code: grpc.status.INTERNAL,
                    message: `Laravel respondió con HTTP ${response.status}.`,
                });
                return;
            }

            const producto = await response.json();

            callback(null, {
                id: producto.id,
                nombre: producto.nombre,
                precio: Number(producto.precio),
                stock: producto.stock,
            });
        } catch (error) {
            callback({
                code: grpc.status.UNAVAILABLE,
                message: `No se pudo consultar Laravel: ${error.message}`,
            });
        }
    },
});

server.bindAsync(
    `127.0.0.1:${port}`,
    grpc.ServerCredentials.createInsecure(),
    (error) => {
        if (error) {
            throw error;
        }

        console.log(`Servidor gRPC escuchando en 127.0.0.1:${port}`);
        server.start();
    },
);
