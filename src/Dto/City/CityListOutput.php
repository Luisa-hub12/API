<?php

namespace App\Dto\City;

use ApiPlatform\Metadata\ApiProperty;

class CityListOutput {
    public function __construct(
        #[ApiProperty(schema: [
            'type' => 'string',
            'format' => 'uuid',
            'description' => 'Identifiant unique de la ville.'
        ])]
        public string $id,
        #[ApiProperty(description: 'Nom de la ville.')]
        public string $name,
    ) {

    }
}
