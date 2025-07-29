<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pagos';
    
    protected $fillable = [
        'pedido_id',
        'monto',
        'fecha',
        'metodo_pago_id',
        'estado_pago_id',
        'referencia_externa',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Get the payment method that owns the payment.
     */
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    /**
     * Get the payment status that owns the payment.
     */
    public function estadoPago()
    {
        return $this->belongsTo(EstadoPago::class, 'estado_pago_id');
    }
} 