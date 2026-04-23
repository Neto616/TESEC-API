<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CotizacionRequest extends FormRequest
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
            case "editar":
            case "crear":
                return [
                    "titulo" => ["sometimes", "nullable", "string", "max:255"],
                    "consideraciones" => ["required", "string", "max:255"],
                    "id_cliente" => [
                        "sometimes",
                        "nullable",
                        "exists:clientes,id",
                    ],
                    "useIVA" => ["required", "boolean"],
                    "useISR" => ["required", "boolean"],
                    "productos" => ["required", "array"],
                    "productos.*.id" => [
                        "required",
                        "integer",
                        "exists:productos,id",
                    ],
                    "productos.*.cantidad" => [
                        "required",
                        "numeric",
                        "between:0.01,9999.99",
                    ],
                ];
            case "changeEstatus":
                return [
                    "estatus" => ["required", "integer", "in:0,1,2"],
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "id_cliente.exists" => "El cliente no existe",
        ];
    }
}
