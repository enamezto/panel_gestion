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
            'id_cliente' => 'required|exists:clientes,id',
            'nombre'     => 'required|string|max:255', // Nombre del PC
            'host'       => 'nullable|string|max:255',
            'ip'         => 'nullable|string|max:45',
        ];
    }
    public function messages(): array
    {
        return [
            'id_cliente.exists' => 'El cliente especificado no existe en nuestra base de datos.',
            'nombre.required'   => 'El nombre de la máquina es obligatorio para el registro.',
        ];
    }
}
