<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimiento_inventarios';
    protected $fillable = ['id', 'fk_producto', 'tipo', 'cantidad'];
    protected $primaryKey = 'id';

    public function Productos() {
        return $this->belongsTo(Productos::class, 'fk_producto');
    }
}
