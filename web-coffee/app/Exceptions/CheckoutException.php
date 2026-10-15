<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Kesalahan bisnis pada alur keranjang/checkout.
 * Pesannya aman ditampilkan langsung ke pelanggan.
 */
class CheckoutException extends RuntimeException
{
    public static function emptyCart(): self
    {
        return new self('Keranjang masih kosong. Pilih menu dulu sebelum checkout.');
    }
}
