<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';
    protected $fillable = ['id', 'nombre_producto', 'descripcion_producto', 'precio_unitario', 'cant_inventario'];
    protected $primaryKey = 'id';

    public function movimiento() {
         return $this->hasMany(MovimientoInventario::class, 'fk_producto');
    }
}
