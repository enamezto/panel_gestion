<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desarrollo extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'activo'
    ];

    public function versiones(){
        return $this->hasMany(DesarrolloVersion::class, 'id_dev');
    }
    public function actual(){
        return $this->hasOne(DesarrolloActual::class, 'id_dev');
    }
    public function advertencias(){
        return $this->hasMany(DesarrolloAdvertenciaRestriccion::class, 'id_dev');
    }
    public function licencias(){
        return $this->hasMany(ClienteDesarrollo::class, 'id_dev');
    }
    public function dependencias(){
        return $this->hasMany(DesarrolloDependencia::class, 'id_dev_dependencia');
    }
}
