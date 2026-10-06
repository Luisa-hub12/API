<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Service\CartService;
use Symfony\Bundle\SecurityBundle\Security;

final class CartCollectionProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly CartService $cartService,
    ){}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return [];
        }

        $cart = $this->cartService->findActiveFor($user);

        if (null === $cart) {
            return [];
        }
        return array_map($this->cartService->toDetails(...), [$cart]);
    }
}
