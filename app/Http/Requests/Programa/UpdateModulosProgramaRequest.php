<?php

namespace App\Http\Requests\Programa;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdateModulosProgramaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'modulos' => ['required', 'array', 'min:1'],
            'modulos.*.id' => ['nullable', 'integer', 'exists:modulo,id'],
            'modulos.*.titulo' => ['required', 'string', 'max:255'],
            'modulos.*.temario' => ['nullable', 'string'],
            'modulos.*.orden' => ['nullable', 'integer'],
            'modulos.*.plan' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value instanceof \Illuminate\Http\UploadedFile) {
                        $allowedMimes = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (!in_array($extension, $allowedMimes)) {
                            $fail("El archivo del plan debe estar en formato PDF, Word o Imagen.");
                        }
                        if ($value->getSize() > 10240 * 1024) { // 10MB
                            $fail("El archivo del plan no debe superar los 10MB.");
                        }
                    } elseif (!is_string($value) && !is_null($value)) {
                        $fail("El campo plan debe ser un archivo válido o una ruta de texto.");
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'modulos.required' => 'Debe enviar una lista de módulos.',
            'modulos.*.titulo.required' => 'El título del módulo es obligatorio.',
            'modulos.*.id.exists' => 'El módulo especificado no existe.',
        ];
    }
}
