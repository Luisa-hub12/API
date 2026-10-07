<?php

namespace App\Service;

use App\Dto\Cart\CartAddLineInput;
use App\Dto\Cart\CartDetailsOutput;
use App\Dto\Cart\CartLineOutput;
use App\Dto\Cart\CartPayOutput;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Enum\CartStatus;
use App\Entity\Ticket;
use App\Entity\User;
use App\Exception\Cart\CartAlreadyPaidException;
use App\Exception\Cart\CartEmptyException;
use App\Exception\Cart\CartLineNotFoundException;
use App\Exception\Cart\CartNotFoundException;
use App\Exception\Trip\TripNotFoundException;
use App\Repository\CartRepository;
use App\Service\Utils\AuditService;
use Symfony\Component\Uid\Uuid;

class CartService
{

    public function __construct(
        private readonly TripService $tripService,
        private readonly CartRepository $cartRepository,
        private readonly AuditService  $audit,
        private readonly TicketService $ticketService,
    )
    {}

    public function toLine(CartItem $item): CartLineOutput {
        $trip = $item->getTrip();

        return new CartLineOutput(
            id: $item->getId(),
            trip: $this->tripService->toList($trip),
            passengers: $item->getPassengers(),
            subtotal: $trip->getPrice() * $item->getPassengers(),

        );

    }

    public function toDetails(Cart $cart): CartDetailsOutput {
        $lines = array_map($this->toLine(...), $cart->getItems()->toArray());

        return new CartDetailsOutput(
            id: $cart->getId(),
            status: $cart->getStatus(),
            items: $lines,
            total: array_sum(array_column($lines, 'subtotal')),
            createdAt: $cart->getCreatedAt(),
        );

    }
    public function findActiveFor(User $user): ?Cart
    {
        return $this->cartRepository->findActiveFor($user);
    }

    public function open(User $user): Cart {
        $existing = $this->findActiveFor($user);
        if (null !== $existing) {
            return $existing;
        }
        $cart = new Cart();
        $this->audit->stampCreation($cart);
        $this->cartRepository->persist($cart);
        $this->cartRepository->flush();

        return $cart;
    }

    /**
     * @throws CartNotFoundException
     */
    public function findOneById(Uuid $id): ?Cart
    {
        $cart = $this->cartRepository->find($id);

        if (null === $cart) {
            throw new CartNotFoundException();
        }
        return $cart;
    }

    /**
     * Adds a line to this cart and returns the cart itself.
     *
     * @throws TripNotFoundException when no trip carries the submitted identifier
     * @throws CartAlreadyPaidException when the cart is no longer modifiable
     */
    public function addLine(Cart $cart, CartAddLineInput $input): Cart {

        if($cart->getStatus() === cartStatus::Paid) {
            throw new CartAlreadyPaidException();
        }

        $trip = $this->tripService->findOneById(Uuid::fromString($input->tripId));

        $item = new CartItem()
            ->setTrip($trip)
            ->setPassengers($input->passengers);

        $this->audit->stampCreation($item);

        $cart->addItem($item);

        $this->cartRepository->flush();

        return $cart;
    }

    /**
     * Soft-deletes a line of this cart, and the cart itself when it was the last one.
     *
     * @throws CartAlreadyPaidException  when the cart is no longer modifiable
     * @throws CartLineNotFoundException when this cart carries no such line
     */
    public function removeLine(Cart $cart, Uuid $lineId): void {
        // 1. La garde du panier non modifiable / déjà payé en premier
        if (CartStatus::Paid === $cart->getStatus()) {
            throw new CartAlreadyPaidException();
        }

        // 2. Recherche de la ligne dans la collection du panier (jamais par repository)

        $line =  $cart
            ->getItems()
            ->findFirst(static fn($_, $item): bool => $item->getId()->equals($lineId)
            );

        if (null === $line) {
            throw new CartLineNotFoundException();
        }

        // 4. Estampillage de la suppression de la ligne via markDeleted()
        $this->audit->markDeleted($line);

        // 5. Décompte des lignes vivantes basé sur leur date de suppression (deletedAt === null)
        $alive = $cart
            ->getItems()
            ->filter(
                static fn(CartItem $item): bool => null === $item->getDeletedAt(),
            );

        // 6. Si c'était la dernière ligne vivante, le panier part avec elle dans le même mouvement
        if ($alive->isEmpty()) {
            $this->audit->markDeleted($cart);
        }

        // Synchronisation globale par le repository du panier
        $this->cartRepository->flush();

    }

    /**
     * Pays this cart: issues its tickets and marks it as paid, in a single write.
     *
     * @return Ticket[] the tickets issued
     *
     * @throws CartAlreadyPaidException when the cart has already been paid
     * @throws CartEmptyException       when the cart carries no line
     */
    public function pay(Cart $cart): array {
        // garde du panier déjà payé

        if (CartStatus::Paid === $cart->getStatus()) {
            throw new CartAlreadyPaidException();
        }

        if ($cart->getItems()->isEmpty()) {
            throw new CartEmptyException();
        }
        $tickets = $this->ticketService->issue($cart);

        $cart->setStatus(CartStatus::Paid);

        $this->audit->stampUpdate($cart);

        $this->cartRepository->flush();

        return $tickets;

    }

    /**
     * Returns the confirmation reference of this cart, derived from its identity.
     */
    public function confirmationOf(Cart $cart): string {
        $suffix = strtoupper(substr($cart->getId()->toRfc4122(), -6));

        return sprintf('ONTB-%s-%s', $cart->getCreatedAt()->format('Y'), $suffix);
    }

    /**
    * Maps a paid cart and its tickets onto the payload served by the payment endpoint.
    *
    * @param Ticket[] $tickets
    */
    public function toPayment(Cart $cart, array $tickets): CartPayOutput {
        return new CartPayOutput(
            confirmation: $this->confirmationOf($cart),
            tickets: array_map($this->ticketService->toList(...), $tickets),
        );
    }


}
