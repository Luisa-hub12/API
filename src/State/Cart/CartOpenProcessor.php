<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Exception\AccessDeniedException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Cart\CartDetailsOutput;
use App\Service\CartService;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<mixed, CartDetailsOutput>
 */
final class CartOpenProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly CartService $cartService,
    ) {
    }

    /**
     * Serves the pending cart of the authenticated traveler, opening one when there is none.
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CartDetailsOutput
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }
        return $this->cartService->toDetails($this->cartService->open($user));
    }
}
