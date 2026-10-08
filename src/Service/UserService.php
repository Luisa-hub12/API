<?php

namespace App\Service;

use App\Dto\User\UserDetailsOutput;
use App\Dto\User\UserProfilePictureInput;
use App\Dto\User\UserRegisterInput;
use App\Entity\Enum\DocumentType;
use App\Entity\User;
use App\Exception\User\EmailAlreadyUsedException;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\Utils\AuditService;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly AuditService $audit,
        private readonly LoggerInterface $domainLogger,
        private readonly DocumentService $documentService,
    ) {}

    public function toDetails(User $user): UserDetailsOutput
    {
        $picture = $user->getProfilePicture();

        return new UserDetailsOutput(
            id: $user->getId(),
            email: $user->getEmail(),
            firstName: $user->getFirstname(),
            lastName: $user->getLastname(),
            createdAt: $user->getCreatedAt(),
            profilePictureUrl: null === $picture
                ? null
                : $this->documentService->toSignedUrl($picture),
        );

    }
    public function findOneByEmail(string $email): ?User
    {
        return $this->userRepository->findOneByEmail($email);
    }
    public function register(UserRegisterInput $input): User {

        $existingUser = $this->findOneByEmail($input->email);
        if ($existingUser) {
            $this->domainLogger->error("User registration - conflict : user already used");
            throw new EmailAlreadyUsedException();
        }
        $user = new User()
            ->setEmail($input->email)
            ->setFirstName($input->firstName)
            ->setLastName($input->lastName);

        $user->setPassword($this->passwordHasher->hashPassword($user, $input->password));

        $this->audit->stampCreation($user);

        $this->userRepository->persist($user);
        $this->userRepository->flush();

        $this->domainLogger->info('User registered', ['user_id' => $user->getId()->toRfc4122()]);


        return $user;

    }

    /**
     * Stores a new profile picture for this user and soft-deletes the previous one,
     * in a single write.
     */
    public function changeProfilePicture(User $user, UserProfilePictureInput $input): void {

        $document = $this->documentService->store($input->file, DocumentType::ProfilePicture);

        $previous = $user->getProfilePicture();

        if (null !== $previous) {
            $this->documentService->softDelete($previous);
        }

        $user->setProfilePicture($document);
        $this->audit->stampUpdate($user);
        $this->userRepository->persist($user);

        $this->userRepository->flush();
    }


}
