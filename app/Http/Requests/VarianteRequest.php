<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VarianteRequest extends FormRequest
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
                'id_producto'              => ['required', 'exists:productos,id'],
                'sku'                      => ['required', 'string', 'distinct', 'min:1', 'max:255'],
                'atributos'                => ['required', 'array', 'min:1'],
                'atributos.*.id'           => ['required', 'exists:atributos,id'],
                'atributos.*.valor'        => ['required', 'string', 'min:1', 'max:255'],
                'proveedor'                => ['required', 'array', 'min:1'],
                'proveedor.id'             => ['required', 'exists:proveedores,id'],
                'proveedor.precio'         => ['required', 'numeric', 'between:0,999999.99'],                    
                'proveedor.precio_publico' => ['required', 'numeric', 'between:0,999999.99'],
                'inventario'               => ['nullable', 'array'],
                'inventario.isOnStorage'   => ['required_with:inventario', 'boolean'],
                'inventario.cantidad'      => ['required_with:inventario', 'integer', 'min:1']
            ];
            case 'editar': return [
                'sku'                      => ['sometimes', 'required', 'string', 'max:255'],
                'atributos'                => ['sometimes', 'required', 'array', 'min:1'],
                'atributos.*.id'           => ['required', 'exists:atributos,id'],
                'atributos.*.valor'        => ['required', 'string', 'min:1', 'max:255'],
                'proveedor'                => ['required', 'array', 'min:1'],
                'proveedor.id'             => ['required', 'exists:proveedores,id'],
                'proveedor.precio'         => ['required', 'numeric', 'between:0,999999.99'],
                'proveedor.precio_publico' => ['required', 'numeric', 'between:0,999999.99'],
                'inventario'               => ['nullable', 'array'],
                'inventario.isOnStorage'   => ['required_with:inventario', 'boolean'],
                'inventario.cantidad'      => ['required_with:inventario', 'integer', 'between:0,999999']
            ];
            default: return [];
        }
    }

    public function messages(){
        return [
            'id_producto.required'               => 'Se debe especificar el producto para crear la variante',
            'id_producto.exists'                => 'El producto no existe',
            'sku.required'                      => 'El SKU es obligatorio en una variante.',
            'atributos.required'                => 'Los atributos son obligoatorios en una variante.',
            'atributos.*.id.exists'             => 'El atributo no existe.',
            'atributos.*.valor.required'        => 'Se necesita especificar el valor del atributo.',
            'proveedor.required'                => 'Se necesita especificar el proveedor.',
            'proveedor.id.exists'               => 'El proveedor no existe.',
            'proveedor.precio.required'         => 'Se debe especificar el precio de la variante',
            'proveedor.precio.between'          => 'El precio excede del rango a manejar (0.01 - 999,999.99)',
            'proveedor.precio_publico.required' => 'Se debe especificar el precio de la variante',
            'proveedor.precio_publico.between'  => 'El precio excede del rango a manejar (0.01 - 999,999.99)',
            'inventario.isOnStorage.required'   => 'Se debe especificar si el producto esta o no en inventario',
            'inventario.cantidad.required'      => 'Se debe especificar la cantidad que cuenta del producto ',
            'inevntario.cantidad.beween'        => 'La cantidad excede del rango a manejar (0 - 999,999.99)',
        ];
    }
}
