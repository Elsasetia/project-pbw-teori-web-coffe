<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Dipakai untuk tambah DAN edit menu.
 * Saat edit, route parameter {product} terisi sehingga aturan unique mengabaikan menu itu sendiri.
 */
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Akses sudah dijaga middleware "auth" + "admin" di route.
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Checkbox yang tidak dicentang tidak ikut terkirim -> jadikan false.
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100', Rule::unique('products', 'name')->ignore($this->route('product'))],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'name' => 'nama menu',
            'description' => 'deskripsi',
            'price' => 'harga',
            'stock' => 'stok',
            'image' => 'gambar',
        ];
    }
}
