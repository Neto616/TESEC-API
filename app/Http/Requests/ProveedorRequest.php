<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProveedorRequest extends FormRequest
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
        $proveedor = $this->route('proveedor');
        $id = is_object($proveedor) ? $proveedor->id : $proveedor;

        switch($method){
            case 'crear':  return [
                'nombre'  => [
                    'required', 'string', 
                    'min:1', 'max:255',
                    Rule::unique('proveedores', 'nombre')
                    ->where(fn ($q) => $q->where('estatus', config('constants.common_status.activo')))
                ],
                'correo_contacto' => ['email', 'string'],
                'telefono' => ['nullable', 'string', 'min:8', 'max:15'],
                'logo'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'estatus' => ['required', 'integer', 'in:0,1'],
                'rfc' => [
                    'required',
                    'string',
                    'uppercase', // Recomendado que siempre sea mayúsculas
                    'regex:/^[A-Z&Ñ]{3,4}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{3}$/'
                ],
                'direccion' => ['sometimes', 'string', 'min:0', 'max:255']
            ];
            case 'editar': return [
                'nombre' => [
                    'sometimes', 'string',
                    'min:1', 'max:255',
                    Rule::unique('proveedores', 'nombre')
                        ->where(fn($q) => $q
                        ->where('id', '<>', $id)
                        ->where('estatus', config('constants.common_status.activo'))
                    )
                ],
                'correo_contacto' => ['sometimes', 'email', 'string'],
                'telefono' => ['nullable', 'string', 'min:8', 'max:15'],
                'rfc' => [
                    'sometimes',
                    'string',
                    'uppercase', // Recomendado que siempre sea mayúsculas
                    'regex:/^[A-Z&Ñ]{3,4}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{3}$/'
                ],
                'direccion' => ['sometimes', 'string', 'min:0', 'max:255']
            ];
            default: return [];
        }
    }

    public function messages(){
        return [
            'nombre.required' => "Se debe especificar el nombre del proveedor",
            'nombre.unique'   => "Ya existe un proveedor con ese nombre",
            'logo.mimes'      => "El logo debe ser de un formato jpeg, png, jpg o gif",
            'estatus.in'      => "El estatus debe ser activo o inactivo",
            'correo_contacto.required' => "Se requiere de un correo para contactar al proveedor",
        ];
    }
}
