<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\MovimientoInventarioController;

//ruta para productos
Route::get('/productos/select', [ProductosController::class, 'select']);
Route::post('/productos/store', [ProductosController::class, 'store']);
Route::put('/productos/update/{id}', [ProductosController::class, 'update']);
Route::delete('/productos/delete/{id}', [ProductosController::class, 'delete']);
Route::get('/productos/show/{id}', [ProductosController::class, 'show']);

//ruta para movimientos de inventario
Route::get('/movimientos/select', [MovimientoInventarioController::class, 'select']);
Route::post('/movimientos/store', [MovimientoInventarioController::class, 'store']);
Route::put('/movimientos/update/{id}', [MovimientoInventarioController::class, 'update']);

// Example route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
