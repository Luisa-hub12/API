<?php

namespace App\Dto\Cart;
use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Enum\CartPaymentMethod;
use Symfony\Component\Validator\Constraints as Assert;
final class CartPayInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [self::class, 'getPaymentMethods'])]
        #[ApiProperty(required: true, schema: [
            'type' => 'string',
            'enum' => ['card', 'voucher'],
            'description' => 'Moyen de paiement déclaré : card (carte) ou voucher (billet)'
        ]),]
        public string $paymentMethod,
    )
    {
    }

    public static function getPaymentMethods(): array
    {
        return array_map(fn(CartPaymentMethod $method) => $method->value, CartPaymentMethod::cases());

    }

}
