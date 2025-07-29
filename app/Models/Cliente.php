<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';
    
    protected $fillable = [
        'nombre',
        'correo_electronico',
        'telefono',
        'direccion',
        'password_hash',
        'last_login_at',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the orders for this client.
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }
} 