<?php

namespace App\Http\Requests;

use App\Rules\CedulaEcuatoriana;
use Illuminate\Foundation\Http\FormRequest;

// Esta clase se encarga de validar los datos del formulario de profesores antes de guardar en la base
class StoreProfesorRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Dejamos en true para permitir que el usuario envie el formulario
        return true;
    }

    public function rules(): array
    {
        return [
            // La cedula debe ser obligatoria, de 10 caracteres, no repetida y valida con modulo 10
            'cedula' => [
                'required',
                'string',
                'size:10',
                'unique:profesores,cedula',
                new CedulaEcuatoriana(),
            ],
            // El nombre y apellido son obligatorios y solo admiten letras
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            // El correo debe tener formato valido y no estar registrado por otro profesor
            'correo' => [
                'required',
                'string',
                'email',
                'max:150',
                'unique:profesores,correo',
            ],
            // Lista de materias que el profesor puede dictar (opcional)
            'materias' => ['nullable', 'array'],
            'materias.*' => ['exists:materias,id'],
        ];
    }

    // Mensajes en espanol claro cuando falla alguna regla del formulario
    public function messages(): array
    {
        return [
            'cedula.required' => 'La cédula de identidad es un dato obligatorio.',
            'cedula.size' => 'La cédula debe tener exactamente 10 dígitos.',
            'cedula.unique' => 'Esta cédula ya se encuentra registrada en el sistema de profesores.',
            'nombre.required' => 'El nombre del profesor es obligatorio.',
            'nombre.regex' => 'El nombre solo debe contener letras.',
            'apellido.required' => 'El apellido del profesor es obligatorio.',
            'apellido.regex' => 'El apellido solo debe contener letras.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Debe ingresar un formato de correo electrónico válido.',
            'correo.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'materias.*.exists' => 'Una o más materias seleccionadas no existen en el sistema.',
        ];
    }
}
