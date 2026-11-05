<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cod = 'COD';
    case Bank = 'Bank';
    case Card = 'Card';

    /**
     * Map the value sent by the checkout form (cod, wire, card) to a payment method.
     */
    public static function fromCheckoutValue(?string $value): self
    {
        return match (strtolower(trim((string) $value))) {
            'wire', 'bank' => self::Bank,
            'card' => self::Card,
            default => self::Cod,
        };
    }

    /**
     * Simple text shown to the customer.
     */
    public function label(): string
    {
        return match ($this) {
            self::Cod => 'Cash on Delivery',
            self::Bank => 'Bank Transfer',
            self::Card => 'Card',
        };
    }
}
