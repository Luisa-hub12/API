<?php

namespace App\Dto\Trip;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\City\CityListOutput;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class TripDetailsOutput extends TripListOutput
{
    public function __construct(
        Uuid $id,
        CityListOutput $origin,
        CityListOutput $destination,
        DateTimeImmutable $departureAt,
        int $duration,
        int $price,

        #[ApiProperty(schema:[
            'type' => 'integer',
            'description' => 'Poid maximum de bagage autorisé en kg.',
            'minimum' => 0
        ])]
        public readonly int $maxBaggagesWeightKg,

        #[ApiProperty(schema: [
            'type' => 'string',
            'description' => 'Modèle de catapult utilisé pour le trajet',
        ])]
        public readonly string $cataplutModel,

        #[ApiProperty(schema: [
            'type' => 'string',
            'description' => 'Informations embarquement pour le passager.'
        ])]
        public readonly string $boardingInfo,
    )
    {
        parent::__construct(
            $id,
            $origin,
            $destination,
            $departureAt,
            $duration,
            $price,
        );

    }

}
