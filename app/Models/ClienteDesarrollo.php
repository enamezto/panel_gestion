<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteDesarrollo extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_dev',
        'activo'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    public function desarrollo(){
        return $this->belongsTo(Desarrollo::class, 'id_dev');
    }
    public function estadoActual(){
        return $this->hasOne(ClienteDesarrolloActual::class, 'id_cliente_desarrollo');
    }
    public function historialInstancias(){
        return $this->hasMany(ClienteInstanciaVersion::class, 'id_cliente_desarrollo');
    }
    public function historialVersiones(){
        return $this->hasMany(ClienteDesarrolloVersion::class, 'id_cliente_desarrollo');
    }
}
