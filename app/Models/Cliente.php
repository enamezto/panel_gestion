<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Cliente extends Model
{
    use HasApiTokens;

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'codigo',
        'version_a3erp',
        'activo'
    ];

    public function instancias(){
        return $this->hasMany(ClienteInstancia::class, 'id_cliente');
    }
    public function licencias(){
        return $this->hasMany(ClienteDesarrollo::class, 'id_cliente');
    }

    // Calcula el estado de un cliente: error, desactualizado o actualizado.
    // (Misma lógica que el Dashboard para mantener coherencia)
    public function calcularEstadoGeneral($versionesVigentes = null, $instanciasConErrores = []): string
    {
        if (!$this->activo) {
            return 'inactivo';
        }

        $idsInstancias = $this->instancias->pluck('id')->toArray();

        $tieneErrores = count(array_intersect($idsInstancias, $instanciasConErrores)) > 0;

        if ($tieneErrores) {
            return 'error';
        }

        if(!$versionesVigentes){
            $versionesVigentes = DesarrolloActual::pluck('id_dev_version', 'id_dev');
        }

        $this->loadMissing('licencias.estadoActual');

        foreach ($this->licencias as $licencia) {
            if (!$licencia->activo || !$licencia->estadoActual) {
                continue;
            }
            $versionActual = $licencia->estadoActual->id_dev_version;
            $versionVigente = $versionesVigentes[$licencia->id_dev] ?? null;

            if ($versionVigente && $versionActual != $versionVigente) {
                return 'desactualizado';
            }
        }

        return 'actualizado';
    }
}
