<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesarrolloVersion extends Model
{
    protected $table = 'desarrollo_versiones';

    public $timestamps = false;

    protected $fillable = [
        'id_dev',
        'version_major',
        'version_minor',
        'version_patch',
        'descripcion_cambios',
        'requiere_parada',
        'version_a3erp_min',
        'version_a3erp_max',
        'hash',
        'ruta'
    ];

    public function desarrollo(){
        return $this->belongsTo(Desarrollo::class, 'id_dev');
    }
    public function adjunto(){
        return $this->hasMany(DesarrolloAdjunto::class, 'id_dev_version');
    }
    public function dependencias(){
        return $this->hasMany(DesarrolloDependencia::class, 'id_dev_version');
    }
    public function instaladoEnClientesActual(){
        return $this->hasMany(ClienteDesarrolloActual::class, 'id_dev_version');
    }
    public function historialClientes(){
        return $this->hasMany(ClienteDesarrolloVersion::class, 'id_dev_version');
    }
    public function historialInstancias(){
        return $this->hasMany(ClienteInstanciaVersion::class, 'id_dev_version');
    }
    public function esLaActualDelCatalogo(){
        return $this->hasMany(DesarrolloActual::class, 'id_dev_version');
    }
}
