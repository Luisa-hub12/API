<?php

namespace App\State\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\User\UserDetailsOutput;
use App\Service\UserService;

final class UserRegisterProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserService $userService,
    )
    {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserDetailsOutput
    {
        // 1. Register the user
        // 2. transformer le fuser qu'on vient de créer en UserDetailOutput
        // 3. Retourner le UserDetailsOutput

        return $this->userService->toDetails($this->userService->register($data));
    }
}
