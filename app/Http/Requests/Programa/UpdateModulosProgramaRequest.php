<?php

namespace App\Http\Requests\Programa;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdateModulosProgramaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Si la petición viene como un array JSON indexado directo [{...}, {...}],
     * lo envolvemos dentro del key 'modulos' antes de validar
     */
    protected function prepareForValidation(): void
    {
        $data = json_decode($this->getContent(), true);

        if (is_array($data) && array_is_list($data)) {
            $this->replace(['modulos' => $data]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'modulos' => ['required', 'array', 'min:1'],
            'modulos.*.id' => ['nullable', 'integer', 'exists:modulo,id'],
            'modulos.*.titulo' => ['required', 'string', 'max:255'],
            'modulos.*.temario' => ['nullable', 'string'],
            'modulos.*.orden' => ['nullable', 'integer'],
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
