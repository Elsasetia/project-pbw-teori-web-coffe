<?php

namespace App\Exceptions;

use App\Models\Product;

class InsufficientStockException extends CheckoutException
{
    public static function forProduct(Product $product): self
    {
        return new self("Stok {$product->name} tidak cukup atau menu sedang tidak tersedia.");
    }
}
