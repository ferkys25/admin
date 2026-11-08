<?php

namespace App\Http\Requests;

use App\Rules\CedulaEcuatoriana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfesorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $profesorId = $this->route('profesore') ?? $this->route('profesor');
        if (is_object($profesorId)) {
            $profesorId = $profesorId->id;
        }

        return [
            'cedula' => [
                'required',
                'string',
                'size:10',
                Rule::unique('profesores', 'cedula')->ignore($profesorId),
                new CedulaEcuatoriana(),
            ],
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'correo' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('profesores', 'correo')->ignore($profesorId),
            ],
            'materias' => ['nullable', 'array'],
            'materias.*' => ['exists:materias,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cedula.required' => 'La cédula de identidad es un dato obligatorio.',
            'cedula.size' => 'La cédula debe tener exactamente 10 dígitos.',
            'cedula.unique' => 'Esta cédula ya se encuentra registrada por otro profesor.',
            'nombre.required' => 'El nombre del profesor es obligatorio.',
            'apellido.required' => 'El apellido del profesor es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Debe ingresar un formato de correo electrónico válido.',
            'correo.unique' => 'Este correo electrónico ya se encuentra registrado por otro profesor.',
        ];
    }
}
