import sys

import grpc

import producto_pb2
import producto_pb2_grpc


def main():
    producto_id = int(sys.argv[1]) if len(sys.argv) > 1 else 1
    with grpc.insecure_channel("127.0.0.1:50051") as channel:
        stub = producto_pb2_grpc.ProductoServiceStub(channel)

        try:
            producto = stub.ObtenerProducto(
                producto_pb2.ProductoRequest(id=producto_id)
            )
            print(producto)
        except grpc.RpcError as error:
            print(error.code(), error.details())
            raise SystemExit(1) from error


if __name__ == "__main__":
    main()
