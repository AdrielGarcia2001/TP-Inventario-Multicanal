<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProductoController;
use App\Models\Producto;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('productos', ProductoController::class);

Route::get('/interno/productos/{producto}', function (Request $request, Producto $producto) {
    $clave = (string) config('services.grpc_internal_key');

    abort_if(
        $clave === '' || ! hash_equals($clave, (string) $request->header('X-Internal-Key')),
        403,
    );

    return response()->json($producto);
});
