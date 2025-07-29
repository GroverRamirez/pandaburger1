<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    use HasFactory;

    protected $table = 'estados_pedido';
    
    protected $fillable = [
        'nombre',
    ];

    /**
     * Get the orders for this status.
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'estado_id');
    }
} 