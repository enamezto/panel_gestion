<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesarrolloAdvertenciaRestriccion extends Model
{
    protected $table = 'desarrollo_advertencia_restricciones';

    public $timestamps = false;

    protected $fillable = [
        'id_dev',
        'id_adj',
        'mensaje_advertencia',
        'activo'
    ];

    public function desarrollo()
    {
        return $this->belongsTo(Desarrollo::class, 'id_dev');
    }

    public function adjunto()
    {
        return $this->belongsTo(Adjunto::class, 'id_adj');
    }
}
