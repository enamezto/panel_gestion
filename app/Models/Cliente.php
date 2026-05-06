<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'codigo',
        'version_a3erp',
        'activo'
    ];

    public function instancias(){
        return $this->hasMany(ClienteInstancia::class, 'id_cliente');
    }
    public function licencias(){
        return $this->hasMany(ClienteDesarrollo::class, 'id_cliente');
    }
}
