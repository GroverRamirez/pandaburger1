<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodoPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'metodos_pago';
    
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Get the payments for this method.
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'metodo_pago_id');
    }
} 