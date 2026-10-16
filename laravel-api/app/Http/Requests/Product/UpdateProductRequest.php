<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price'       => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'        => 'El nombre del producto es obligatorio.',
            'name.max'             => 'El nombre no puede exceder 255 caracteres.',
            'price.required'       => 'El precio es obligatorio.',
            'price.numeric'        => 'El precio debe ser un número válido.',
            'price.min'            => 'El precio no puede ser negativo.',
            'stock.required'       => 'El stock es obligatorio.',
            'stock.integer'        => 'El stock debe ser un número entero.',
            'stock.min'            => 'El stock no puede ser negativo.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists'   => 'La categoría seleccionada no es válida.',
        ];
    }
}
