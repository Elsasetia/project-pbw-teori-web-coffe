<?php

namespace App\Support;

use App\Models\Product;

/**
 * Satu baris di keranjang: produk + jumlah.
 * Immutable, jadi aman dipakai di view tanpa takut berubah.
 */
final class CartLine
{
    public function __construct(
        public readonly Product $product,
        public readonly int $quantity,
    ) {
    }

    public function subtotal(): int
    {
        return $this->product->price * $this->quantity;
    }
}
