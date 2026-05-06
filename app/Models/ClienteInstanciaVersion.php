<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteInstanciaVersion extends Model
{
    protected $table = 'cliente_instancia_versiones';

    public $timestamps = false;

    protected $fillable = [
        'id_instancia',
        'id_dev_version',
        'id_cliente_desarrollo',
        'resultado',
        'observaciones',
        'fecha_descarga',
        'fecha_actualizacion'
    ];

    public function instancia(){
        return $this->belongsTo(ClienteInstancia::class, 'id_instancia');
    }
    public function version(){
        return $this->belongsTo(DesarrolloVersion::class, 'id_dev_version');
    }
    public function contrato(){
        return $this->belongsTo(ClienteDesarrollo::class, 'id_cliente_desarrollo');
    }


}
