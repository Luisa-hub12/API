<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class CartAddLineInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[ApiProperty(required: true, schema: [
            'type' => 'string',
            'format' => 'uuid',
            'description' => 'Identifiant du lancer à ajouter.'

        ]
        )]
        public string $tripId,

        #[Assert\NotNull]
        #[Assert\Positive]
        #[ApiProperty( required: true, schema: [
            'type' => 'integer',
            'minimum' => 1,
            'description' => 'Nombre de places sur ce lancer. Chaque place donnera un billet.'
        ])]
        public int $passengers
    )
    {
    }

}
