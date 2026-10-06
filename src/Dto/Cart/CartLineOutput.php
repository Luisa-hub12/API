<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Trip\TripListOutput;
use Symfony\Component\Uid\Uuid;

final class CartLineOutput
{
    public function __construct(
        #[ApiProperty(schema: [
            "type" => "string",
            "format" => "uuid",
            "Description" => "Identifiant de la ligne.",
        ])]
        public readonly Uuid $id,
        #[ApiProperty(schema: [
            "type" => "object",
            "description" => "Le lancer réservé.",
            ])]
        public readonly TripListOutput $trip,
        #[ApiProperty(schema: [
            "type" => "integer",
            "description" => "Nombre de places.",
            "min" => 1,
        ])]
        public readonly int $passengers,
        #[ApiProperty(schema: [
            "type" => "integer",
            "description" => "Prix du lancer multiplié par le nombre de places, en centimes.",

        ])]
        public readonly int $subtotal,
    ) {
    }
}
