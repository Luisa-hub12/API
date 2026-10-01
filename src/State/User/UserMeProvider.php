<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\SecurityBundle\Security;

final class UserMeProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly UserService $userService,
    ) {
    }

    // la signature de provide() est documentée par API Platform : allez la relever
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }
        return $this->userService->toDetails($user);
    }
}
