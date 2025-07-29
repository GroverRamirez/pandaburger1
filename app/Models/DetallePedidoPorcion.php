<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePedidoPorcion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalle_pedido_porciones';
    
    protected $fillable = [
        'detalle_pedido_id',
        'porcion_producto_id',
        'cantidad',
    ];

    /**
     * Get the order detail that owns the portion detail.
     */
    public function detallePedido()
    {
        return $this->belongsTo(DetallePedido::class, 'detalle_pedido_id');
    }
} 