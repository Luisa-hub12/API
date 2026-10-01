<?php


namespace App\Dto\Trip;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class TripSearchInput
{
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[ApiProperty(required: true, schema: [
            'type' => 'string',
            'format' => 'uuid',
            'description' => 'C\'est une uuid'
        ])]
        public ?string $origin = null,

        #[Assert\NotBlank]
        #[Assert\Uuid]#[ApiProperty(required: true, schema: [
            'type' => 'string',
            'format' => 'uuid',
            'description' => 'C\'est une uuid de la ville de destination'
        ])]
        public ?string $destination = null,

        #[Assert\NotBlank]
        #[Assert\Date]
        #[ApiProperty(required: true, schema: [
            'type' => 'string',
            'format' => 'date',
            'description' => 'date du voyage'
        ])]
        public ?string $date = null,

        #[Assert\NotBlank]
        #[Assert\Positive]
        #[ApiProperty(required: true, schema: [
            'type' => 'integer',
            'description' => 'Nombre de voyageurs'
        ])]
        public ?int $passengers = null,
    )
    {

    }

}
