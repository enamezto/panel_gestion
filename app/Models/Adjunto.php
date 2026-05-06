<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_tipo',
        'descripcion',
        'arbol_carpeta',
        'principal'
    ];

    public function tipo(){
        return $this->belongsTo(AdjuntoTipo::class, 'id_tipo');
    }
    public function versiones(){
        return $this->hasMany(AdjuntoVersion::class, 'id_adj');
    }
    public function advertencias(){
        return $this->hasMany(DesarrolloAdvertenciaRestriccion::class, 'id_adj');
    }
}
