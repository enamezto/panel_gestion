<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesarrolloAdjunto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_dev_version',
        'id_adj_version',
        'obligatorio',
        'orden'
    ];

    public function versionDesarrollo()
    {
        return $this->belongsTo(DesarrolloVersion::class, 'id_dev_version');
    }

    public function versionAdjunto()
    {
        return $this->belongsTo(AdjuntoVersion::class, 'id_adj_version');
    }
}
