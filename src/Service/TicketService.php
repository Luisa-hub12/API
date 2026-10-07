<?php

namespace App\Service;

use App\Dto\Ticket\TicketListOutput;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\TicketRepository;
use App\Service\Utils\AuditService;

class TicketService
{
    public function __construct(
        public readonly TicketRepository $ticketRepository,
        public readonly TripService $tripService,
        public readonly AuditService $audit,
    )
    {
    }

    /**
     * Builds one ticket per reserved seat of this cart, persisted but not flushed.
     *
     * @return Ticket[]
     */
    public function issue(Cart $cart): array
    {
        $issued = [];
        foreach ($cart->getItems() as $line) {
            $issued = array_merge($issued, $this->issueForCartItem($line));
        }
        return $issued;
    }

    private function issueForCartItem(CartItem $cartItem): array
    {
        $issued = [];
        $trip = $cartItem->getTrip();

        for ($seat = 0; $seat < $cartItem->getPassengers(); ++$seat) {
            $ticket = new Ticket();
            $ticket->setTrip($trip);
            $ticket->setCart($cartItem->getCart());
            $ticket->setPrice($trip->getPrice());

            $this->audit->stampCreation($ticket);
            $this->ticketRepository->persist($ticket);

            $issued[] = $ticket;
        }
        return $issued;
    }

    public function toList(Ticket $ticket): TicketListOutput
    {
        return new TicketListOutput(
            id: $ticket->getId(),
            trip: $this->tripService->toList($ticket->getTrip()),
            price: $ticket->getPrice(),
            createdAt: $ticket->getCreatedAt(),
        );

    }

    public function findFor(User $user): array
    {
        return $this->ticketRepository->findFor($user);
    }
}
