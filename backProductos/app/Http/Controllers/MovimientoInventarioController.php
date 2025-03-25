<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\MovimientoInventario;
use App\Models\Productos;

class MovimientoInventarioController extends Controller
{
    //endpoint para obtener todos los movimientos de inventario
    public function select()
    {
        try {
            $movimientos = MovimientoInventario::select(
            'movimiento_inventarios.id',
             'movimiento_inventarios.fk_producto',
             'productos.cantidad as productos',
             'movimiento_inventarios.tipo',
            'movimiento_inventarios.cantidad')
            ->join('productos', 'movimiento_inventarios.fk_producto', '=', 'productos.id')
            ->get();
            if ($movimientos->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No hay movimientos de inventario registrados en la base de datos'
                ]);
            }
            return response()->json([
                'code' => 200,
                'data' => $movimientos
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
    //endpoint para crear un nuevo movimiento de inventario
    public function store(Request $request)
    {
        try {
            $datosValidados = $request->validate([
                'fk_producto' => 'required|exists:productos,id',
                'tipo' => 'required|in:entrada,salida',
                'cantidad' => 'required|numeric|min:1'
            ]);
                //Buscar el producto según el id
            $producto = Productos::find($datosValidados['fk_producto']);
            if (!$producto) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Producto no encontrado'
                ]);
            }
                //actualizar el inventario según el movimiento que se vaya a realizar
            if ($datosValidados['tipo'] === 'entrada') {
                $producto->update(['cant_inventario' => $producto->cant_inventario + $datosValidados['cantidad']]);
            } elseif ($datosValidados['tipo'] === 'salida') {
                if ($producto->cant_inventario < $datosValidados['cantidad']) {
                    return response()->json([
                        'code' => 400,
                        'message' => 'No hay suficiente stock para realizar la salida'
                    ]);
                }
                $producto->update(['cant_inventario' => $producto->cant_inventario - $datosValidados['cantidad']]);
            }
    
            //registrar el movimiento de inventario
            $movimiento = MovimientoInventario::create($datosValidados);
    
            return response()->json([
                'code' => 201,
                'message' => 'Movimiento registrado y stock actualizado correctamente',
                'data' => $movimiento
            ], 201);
    
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 422,
                'message' => 'Los datos enviados no son válidos',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
    
    //endpoint para actualizar los movimientos de inventario
    public function update(Request $request, $id) {
        try {
            $datosValidados = $request->validate([
                'fk_producto' =>'required',
                'tipo' =>'required',
                'cantidad' =>'required'
            ]);
            $movimiento = MovimientoInventario::find($id);
            if (!$movimiento) {
                return response()->json([
                    'code' => 404,
                   'message' => 'Movimiento de inventario no encontrado'
                ]);
            }
            $movimiento->update($datosValidados);
            return response()->json([
                'code' => 200,
               'message' => 'Movimiento de inventario actualizado correctamente',
                'data' => $movimiento
            ]); 
        } catch(ValidationException $e ) {
            return response()->json([
                'code' => 422,
                'message' => 'Los datos enviados no son válidos',
                'errors' => $e-> validator->errors()
            ], 422);
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
}
