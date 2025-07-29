<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pedidos';
    
    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'estado_id',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Get the client that owns the order.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Get the status that owns the order.
     */
    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'estado_id');
    }

    /**
     * Get the order details for this order.
     */
    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    /**
     * Get the payments for this order.
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pedido_id');
    }
} 