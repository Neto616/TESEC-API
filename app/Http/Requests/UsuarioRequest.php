<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
        $metod = $this->route()->getActionMethod();

        switch ($metod) {
            case 'crear': return [
                'nombre'    => ['required', 'string', 'min:0', 'max:255'],
                'apellidos' => ['required', 'string', 'min:0', 'max:255'],
                'email'     => ['required', 'string', 'email', 'min:1', 'max:255'],
                'perfil_id' => ['required', 'integer', 'exists:perfiles,id'],
                'estatus'   => ['required', 'integer', 'in:0,1']
                    // 'password' => ['required', 'string','confirmed','min:8', 'max:255'],
            ];
            case 'editar': return[
                'nombre'    => ['sometimes', 'string', 'min:0', 'max:255'],
                'apellidos' => ['sometimes', 'string', 'min:0', 'max:255'],
                'email'     => ['sometimes', 'string', 'email', 'min:1', 'max:255'],
                'perfil_id' => ['sometimes', 'integer', 'exists:perfiles,id'],
                'estatus'   => ['sometimes', 'integer', 'in:0,1']
            ];
            default: return [];
        }
    }

    public function messages(){
        return [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'email.required'     => 'El correo es obligatorio para crear un usuario.',
            'email.email'        => 'El correo debe tener el formato correcto.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las constraseñas no coinciden.',
            'perfil_id.required' => 'Se debe seleccion el perfil que tenga el usuario.'
        ];
    }
}
