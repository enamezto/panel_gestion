<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NuevaVersionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            // --- DATOS DEL DESARROLLO (Módulo) ---
            // El ID es opcional, pero si lo manda, DEBE existir en la tabla desarrollos
            'idModule'    => 'nullable|integer|exists:desarrollos,id',
            // Si NO manda el idModule, es OBLIGATORIO que mande el nombre para crearlo
            'moduleName'  => 'required_without:idModule|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'nullable|string',
            'active'      => 'nullable|integer|in:0,1',

            // --- DATOS DE LA VERSIÓN PRINCIPAL ---
            'major'        => 'required|integer|min:0',
            'minor'        => 'required|integer|min:0',
            'patch'        => 'required|integer|min:0',
            'date'         => 'required|date',
            'changes'      => 'nullable|string',
            'a3erpmin'     => 'nullable|string',
            'a3erpmax'     => 'nullable|string',
            'requiresStop' => 'required',
            'hash'         => 'required|string',
            'path'         => 'required|string',

            // --- DATOS DE LOS ADJUNTOS (Array) ---
            // Exigimos que venga el array y que tenga al menos 1 adjunto
            'adjuntos'               => 'required|array|min:1',
            // Validamos cada objeto dentro del array usando el asterisco (*)
            'adjuntos.*.tipo'        => 'required|string',
            'adjuntos.*.description' => 'nullable|string',
            'adjuntos.*.ruta'        => 'required|string',
            'adjuntos.*.principal'   => 'nullable|string',
            'adjuntos.*.major'       => 'required|integer|min:0',
            'adjuntos.*.minor'       => 'required|integer|min:0',
            'adjuntos.*.patch'       => 'required|integer|min:0',
            'adjuntos.*.hash'        => 'required|string',
            // Usamos date_format o date si te mandan un formato válido. Con date sobra.
            'adjuntos.*.date'        => 'nullable|string',
            'adjuntos.*.obligatorio' => 'required|integer',
            'adjuntos.*.order'       => 'nullable|integer',
    ];
    }
}
