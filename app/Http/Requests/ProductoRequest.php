<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
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
        $metodo = $this->route()->getActionMethod();

        switch($metodo){
            case 'crear': return [
                'nombre'         => ['required', 'string', 'max:255'],
                'descripcion'    => ['nullable','string', 'min:0', 'max:255'],
                'sku'            => ['sometimes', 'string', 'min:0', 'max:255'],
                'marca'          => ['sometimes', 'string', 'min:0', 'max:255'],
                'link'           => ['sometimes', 'nullable','url'],
                'id_proveedor'   => ['required', 'exists:proveedores,id', 'integer'],
                'precio'         => ['sometimes', 'decimal:0,2', 'between:0.00,99999999.99'],
                'precio_publico' => ['sometimes', 'decimal:0,2', 'between:0.00,99999999.99'],
                'imagen'         => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'estatus'        => ['required', 'integer', 'in:0,1'],
                'cantidad'       => ['sometimes', 'nullable', 'numeric', 'between:0,9999999.99'],
            ];
            case 'editar': return [
                'nombre'         => ['sometimes', 'string', 'max:255'],
                'descripcion'    => ['nullable','string', 'min:0', 'max:255'],
                'sku'            => ['sometimes', 'string', 'min:0', 'max:255'],
                'marca'          => ['sometimes', 'string', 'min:0', 'max:255'],
                'link'           => ['sometimes', 'nullable','url'],
                'id_proveedor'   => ['sometimes', 'exists:proveedores,id', 'integer'],
                'precio'         => ['sometimes', 'decimal:0,2', 'between:0.00,99999999.99'],
                'precio_publico' => ['sometimes', 'decimal:0,2', 'between:0.00,99999999.99'],
                'imagen'         => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'estatus'        => ['sometimes', 'integer', 'in:0,1'],
                'cantidad'       => ['sometimes', 'nullable', 'numeric', 'between:0,9999999.99'],
            ];
            default: return [];
        }
    }
    public function messages()
    {
        return [
            'nombre.required'        => 'El nombre del producto es obligatorio.',
            'descripcion.string'     => 'La descripción debe ser de formato texto.',
            'precio.between'         => 'El precio excede del rango aceptado (0.00 - 99,999,999.99)',
            'precio_publico.between' => 'El precio excede del rango aceptado (0.00 - 99,999,999.99)',
            'imagen.image'           => 'El archivo debe ser una imagen válida.',
            'imagen.mimes'           => 'La imagen debe ser formato: jpg, jpeg, png o webp.',
            'imagen.max'             => 'La imagen no debe pesar más de 2MB.',
            'estatus.in'             => 'El estatus debe ser Activo o Inactivo',
            'cantidad.numeric'       => 'La cantidad de inventario debe ser un número.',
            'cantidad.min'           => 'El inventario no puede ser negativo.',
        ];
    }
}
