<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjuntoVersion extends Model
{
    protected $table = 'adjunto_versiones';

    public $timestamps = false;

    protected $fillable = [
        'id_adj',
        'version_major',
        'version_minor',
        'version_patch',
        'hash'
    ];

    public function adjunto(){
        return $this->belongsTo(Adjunto::class, 'id_adj');
    }
    public function desarrollos(){
        return $this->hasMany(DesarrolloAdjunto::class, 'id_adj_version');
    }
}
