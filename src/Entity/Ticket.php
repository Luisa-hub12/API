<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\Ticket\TicketListOutput;
use App\Entity\Impl\AbstractEntity;
use App\Repository\TicketRepository;
use App\State\Ticket\TicketCollectionProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/tickets',
        // le contrat n'envoie aucun corps sur cette adresse : il n'y a rien à désérialiser
        security: "is_granted('ROLE_USER')",
        // toutes les opérations du panier exigent un jeton, comme `/users/me` à l'étape 5
        output: TicketListOutput::class,
        provider: TicketCollectionProvider::class,

    ),
    ])
]
class Ticket extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(
        type: UuidType::NAME, unique: true
    )]
    private Uuid $id;


    #[ORM\ManyToOne(targetEntity: Trip::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Trip $trip;

    #[ORM\ManyToOne(targetEntity: Cart::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Cart $cart;

    /**
     * The price of a single seat in cents to avoid floating-point rounding errors.
     */
    #[ORM\Column(type: 'integer')]
    private int $price;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function getTrip(): Trip
    {
        return $this->trip;

    }

    public function setTrip(Trip $trip): static {
        $this->trip = $trip;
        return $this;
    }

    public function getCart(): Cart {
        return $this->cart;
    }

    public function setCart(Cart $cart): static {
        $this->cart = $cart;
        return $this;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function setPrice(int $price): static {
        $this->price = $price;
        return $this;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
