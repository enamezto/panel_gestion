<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrarInstanciaRequest extends FormRequest
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
            'nombre'         => 'required|string|max:255',
            'host'           => 'nullable|string|max:255',
            'tipo'           => 'required|in:cliente,server',
            'ip'             => 'nullable|string|max:45',
            'ruta_listados'  => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required'   => 'El nombre de la máquina es obligatorio para el registro.',
            'tipo.required'     => 'El tipo de instancia (cliente/server) es obligatorio.',
            'tipo.in'           => 'El tipo de instancia debe ser "cliente" o "server".',
        ];
    }
}
