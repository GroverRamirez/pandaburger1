<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    
    protected $fillable = [
        'rol_id',
        'nombre',
        'correo_electronico',
        'telefono',
        'password_hash',
        'last_login_at',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'password_hash' => 'hashed',
    ];

    /**
     * Get the role that owns the user.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Get the orders for this user.
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }

    /**
     * Get the user's email for password reset.
     */
    public function getEmailForPasswordReset()
    {
        return $this->correo_electronico;
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
} 