<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles';
    
    protected $fillable = [
        'nombre',
    ];

    /**
     * Get the users for this role.
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol_id');
    }
} 