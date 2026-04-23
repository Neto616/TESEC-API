<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
                'nombre'    => ['required', 'string', 'min:1', 'max:255'],
                'apellidos' => ['required', 'string', 'min:1', 'max:255'],
                'email'     => ['sometimes', 'string', 'email', 'min:8', 'max:255'],
                'telefono'  => ['required', 'string', 'min:10', 'max:15']
            ];
            case 'editar': return [
                'nombre'    => ['sometimes', 'string', 'min:0', 'max:255'],
                'apellidos' => ['sometimes', 'string', 'min:0', 'max:255'],
                'email'     => ['sometimes', 'string', 'email', 'min:1', 'max:255'],
                'telefono'  => ['sometimes', 'string', 'min:10', 'max:15']
            ];
            default: return[];
        };
    }
}
