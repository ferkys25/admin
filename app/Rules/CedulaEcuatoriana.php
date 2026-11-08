<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// Regla personalizada para validar que la cedula sea ecuatoriana real
class CedulaEcuatoriana implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cedula = trim((string) $value);

        // Primero revisamos que tenga 10 digitos y que todos sean numeros
        if (strlen($cedula) !== 10 || !ctype_digit($cedula)) {
            $fail('La cédula debe contener exactamente 10 dígitos numéricos.');
            return;
        }

        // Los dos primeros digitos son la provincia (del 01 al 24)
        $provincia = (int) substr($cedula, 0, 2);
        if ($provincia < 1 || ($provincia > 24 && $provincia !== 30)) {
            $fail('Los dos primeros dígitos de la cédula no corresponden a una provincia válida de Ecuador.');
            return;
        }

        // El tercer digito en una persona normal no puede ser mayor a 5
        $tercerDigito = (int) $cedula[2];
        if ($tercerDigito < 0 || $tercerDigito > 5) {
            $fail('El tercer dígito de la cédula para persona natural debe estar entre 0 y 5.');
            return;
        }

        // Aqui hacemos la multiplicacion del modulo 10
        // Se multiplican los primeros 9 digitos por 2 y por 1 de forma intercalada
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $producto = (int) $cedula[$i] * $coeficientes[$i];
            
            // Si el resultado es 10 o mas le restamos 9
            if ($producto >= 10) {
                $producto -= 9;
            }
            $suma += $producto;
        }

        // Sacamos la decena superior para ver cual debio ser el ultimo digito
        $residuo = $suma % 10;
        $digitoCalculado = ($residuo === 0) ? 0 : (10 - $residuo);
        $digitoVerificador = (int) $cedula[9];

        // Comparamos el digito que calculamos con el que escribio el usuario
        if ($digitoCalculado !== $digitoVerificador) {
            $fail('La cédula ecuatoriana ingresada es inválida (falló la comprobación del dígito verificador Módulo 10).');
        }
    }
}
