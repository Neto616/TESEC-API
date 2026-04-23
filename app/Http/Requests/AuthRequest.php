<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        
        switch($method) {
            case 'iniciarSesion': return [
                'email'    => ['required', 'string', 'email', 'min:1', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'max:255']
            ];
            default: return[];
        }
    }

    public function messages(): array 
    {
        return [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El correo debe tener un formato de correo válido.',
            'email.min'         => 'El correo debe tener al menos un carácter.',
            'email.max'         => 'El correo excede el máximo de caracteres permitido.',
            'email.string'      => 'El correo debe ser una cadena de caracteres.',
            
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max'      => 'La contraseña excede el máximo de caracteres permitido.',
            'password.string'   => 'La contraseña debe ser una cadena de texto.'
        ];
    }
}
