<?php

namespace App\Service;

use App\DTO\Trip\TripListOutput;
use App\DTO\Trip\TripSearchInput;
use App\Entity\Trip;
use App\Repository\TripRepository;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class TripService
{
    public function __construct(
        private readonly CityService $cityService,
        private readonly TripRepository $tripRepository,
    ) {
    }

    /**
     * @return Trip[]
     */
    public function search(TripSearchInput $input): array
    {
        $origin = $this->cityService->findOneById(Uuid::fromString($input->origin));
        $destination = $this->cityService->findOneById(Uuid::fromString($input->destination));
        $date = new DateTimeImmutable($input->date);

        return $this->tripRepository->search($origin, $destination, $date);
    }

    public function toList(Trip $trip): TripListOutput
    {
        return new TripListOutput(
            $trip->getId(),
            $this->cityService->toList($trip->getOrigin()),
            $this->cityService->toList($trip->getDestination()),
            $trip->getDepartureAt(),
            $trip->getDuration(),
            $trip->getPrice(),
        );
    }
}
