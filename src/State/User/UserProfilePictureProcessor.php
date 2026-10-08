<?php

namespace App\State\User;

use ApiPlatform\Metadata\Exception\AccessDeniedException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\Exception\ValidationException;
use App\Dto\User\UserDetailsOutput;
use App\Dto\User\UserProfilePictureInput;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @implements ProcessorInterface<mixed, UserDetailsOutput\>
 */
final class UserProfilePictureProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly UserService $userService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * Validates the uploaded picture, makes it the bearer's profile picture,
     * and serves their details.
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserDetailsOutput
    {
        // 1. Récupération du fichier depuis la requête HTTP
        $file = $context['request']->files->get('file');

        // 2. Construction de l'Input avec le fichier (ou null si ce n'est pas un UploadedFile valide)
        $input = new UserProfilePictureInput($file instanceof UploadedFile ? $file : null);

        // 3. Validation de l'Input (vérifie le NotNull et les contraintes Image)
        $violations = $this->validator->validate($input);

        if (sizeof($violations) > 0) {
            throw new ValidationException($violations);
        }

        // 4. Récupération de l'utilisateur connecté et mise à jour de la photo
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        $this->userService->changeProfilePicture($user, $input);

        // 5. Retour des détails de l'utilisateur
        return $this->userService->toDetails($user);


    }
}
