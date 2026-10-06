<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\CartService;

class CartRemoveLineProcessor implements ProcessorInterface
{
    public function __construct(
        public readonly CartService $cartService,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): null
    {
        $cart = $this->cartService->findOneById($uriVariables['id']);
        $this->cartService->removeLine($cart, $uriVariables['itemId']);

        return null;
    }
}
