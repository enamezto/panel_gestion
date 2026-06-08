<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ClienteInstancia extends Model
{
    use HasApiTokens;

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'nombre',
        'host',
        'tipo',
        'ip',
        'ultima_conexion',
        //'registro_token',
        //'registro_fecha',
        'ruta_listados'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    public function versiones(){
        return $this->hasMany(ClienteInstanciaVersion::class, 'id_instancia');
    }
}
