<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtributoRequest extends FormRequest
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
        $method = $this->route()->getActionMethod();
        switch ($method) {
            case 'crear': return [
                'titulo'  => 'required|string|max:100',
                'estatus' => 'required|integer|in:0,1'
            ];
            case 'editar': return [
                'titulo'  => 'sometimes|required|string|max:100',
                'estatus' => 'sometimes|integer|in:0,1',
            ];
            case 'eliminar': return [];
            default: return [];
        }
    }

    public function messages(){
        return [
            'titulo.required' => 'El titulo del atributo es obligatorio',
            'estatus.required' => 'El estatus del atributo es obligatorio'
        ];
    }
}
