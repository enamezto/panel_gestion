<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteDesarrolloActual extends Model
{
    protected $table = 'cliente_desarrollo_actuales';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente_desarrollo',
        'id_dev_version'
    ];

    public function contrato(){
        return $this->belongsTo(ClienteDesarrollo::class, 'id_cliente_desarrollo');
    }
    public function version(){
        return $this->belongsTo(DesarrolloVersion::class, 'id_dev_version');
    }
}
