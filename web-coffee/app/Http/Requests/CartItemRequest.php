<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Tombol "Tambah ke keranjang" di kartu menu tidak mengirim jumlah -> anggap 1.
        $this->merge(['quantity' => $this->input('quantity', 1)]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['quantity' => 'jumlah'];
    }
}
