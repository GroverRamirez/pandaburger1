<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPago extends Model
{
    use HasFactory;

    protected $table = 'estados_pago';
    
    protected $fillable = [
        'nombre',
    ];

    /**
     * Get the payments for this status.
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'estado_pago_id');
    }
} 