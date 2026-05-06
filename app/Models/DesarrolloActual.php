<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesarrolloActual extends Model
{
    protected $table = 'desarrollo_actuales';

    public $timestamps = false;

    protected $fillable = [
        'id_dev',
        'id_dev_version'
    ];

    public function desarrollo()
    {
        return $this->belongsTo(Desarrollo::class, 'id_dev');
    }
    public function version()
    {
        return $this->belongsTo(DesarrolloVersion::class, 'id_dev_version');
    }
}
