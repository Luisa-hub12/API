<?php

namespace App\State\Ticket;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Service\TicketService;
use Symfony\Bundle\SecurityBundle\Security;

class TicketCollectionProvider implements ProviderInterface
{

    public function __construct(
        public readonly TicketService $ticketService,
        public readonly Security $security,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return [];
        }
        $tickets = $this->ticketService->findFor($user);

        return array_map($this->ticketService->toList(...), $tickets);
    }
}
