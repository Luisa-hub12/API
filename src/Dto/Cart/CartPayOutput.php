<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Trip\TripListOutput;
use Symfony\Component\Uid\Uuid;

class CartPayOutput
{
    public function __construct(

        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "Référence de confirmation à confirmer",
        ])]
        public readonly string $confirmation,

        #[ApiProperty(description: "billets émis un par un")]
        /**
         * @var TripListOutput $tickets
         */
        public readonly array $tickets,
    )
    {
    }
}

