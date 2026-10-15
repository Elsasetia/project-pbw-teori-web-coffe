<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Transfer = 'transfer';
    case Qris = 'qris';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Bayar di tempat',
            self::Transfer => 'Transfer bank',
            self::Qris => 'QRIS',
        };
    }
}
