<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesarrolloDependencia extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_dev_version',
        'id_dev_dependencia',
        'version_min',
        'version_max',
        'obligatorio'
    ];

    public function version()
    {
        return $this->belongsTo(DesarrolloVersion::class, 'id_dev_version');
    }

    public function depende()
    {
        return $this->belongsTo(Desarrollo::class, 'id_dev_dependencia');
    }
}
