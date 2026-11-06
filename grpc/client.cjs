const path = require('path');
const grpc = require('@grpc/grpc-js');
const loader = require('@grpc/proto-loader');

const definition = loader.loadSync(path.join(__dirname, 'producto.proto'));
const api = grpc.loadPackageDefinition(definition).inventario;
const port = process.env.GRPC_PORT || '50051';
const id = Number(process.argv[2] || 1);
const client = new api.ProductoService(
    `127.0.0.1:${port}`,
    grpc.credentials.createInsecure(),
);

client.ObtenerProducto({ id }, (error, producto) => {
    client.close();

    if (error) {
        console.error(`${error.code}: ${error.details}`);
        process.exitCode = 1;
        return;
    }

    console.log(producto);
});
