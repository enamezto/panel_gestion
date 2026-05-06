<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjuntoTipo extends Model
{
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function adjuntos(){
        return $this->hasMany(Adjunto::class, 'id_tipo');
    }
}
