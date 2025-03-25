<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Productos;


class ProductosController extends Controller
{
    //endpoint para obtener todos los productos 
    public function select()
    {
        try {
            $productos = Productos::all();
            if ($productos->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No hay productos registrados en la base de datos'
                ]);
            }
            return response()->json([
                'code' => 200,
                'data' => $productos
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
    //endpoint para registrar un producto
    public function store(Request $request)
    {
        try {
            $datosValidados = $request->validate([
                'nombre_producto' =>'required|string',
                'descripcion_producto' =>'required',
                'precio_unitario' =>'required|numeric',
                'cant_inventario' =>'required|numeric'
            ]);
                $productos = Productos::create($datosValidados);
                return response()->json([
                    'code' => 201,
                    'message' => 'Producto registrado correctamente',
                    'data' => $productos
                ], 201);
            
        }  
        catch(ValidationException $e ) {
            return response()->json([
                'code' => 422,
                'message' => 'Los datos enviados no son válidos',
                'errors' => $e-> validator->errors()
            ], 422);
        }
         catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
    //endpoint para actualizar un producto  
    public function update(Request $request, $id) {
        try {
            $datosValidados = $request->validate([
                'nombre_producto' =>'required|string',
                'descripcion_producto' =>'required',
                'precio_unitario' =>'required|numeric',
                'cant_inventario' =>'required|numeric'
            ]);

            $productos = Productos::find($id);
            if (!$productos) {
                return response()->json([
                    'code' => 404,
                   'message' => 'Producto no encontrado'
                ]);
            }
            $productos->update($datosValidados);
            return response()->json([
                'code' => 200,
               'message' => 'Producto actualizado correctamente',
                'data' => $productos
            ], 200);
        
        }
        catch(ValidationException $e ) {
            return response()->json([
                'code' => 422,
                'message' => 'Los datos enviados no son válidos',
                'errors' => $e-> validator->errors()
            ], 422);
        }

        catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
    //endpoint para eliminar un producto
    public function destroy($id) {
        try {
            $productos = Productos::find($id);
            if (!$productos) {
                return response()->json([
                    'code' => 404,
                   'message' => 'Producto no encontrado'
                ]);
            }
            $productos->delete();
            return response()->json([
                'code' => 200,
               'message' => 'Producto eliminado correctamente'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
               'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
    //endpoint para obtener el producto por id 
    public function show($id) {
        try {
            $productos = Productos::find($id);
            if (!$productos) {
                return response()->json([
                    'code' => 404,
                   'message' => 'Producto no encontrado'
                ]);
            }
            return response()->json([
                'code' => 200,
                'data' => $productos
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
               'message' => 'Hubo un error al intentar realizar esta petición. Intente de nuevo por favor',
                'error' => $error->getMessage()
            ]);
        }
    }
}
