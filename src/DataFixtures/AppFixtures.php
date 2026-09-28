<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const string PLAIN_PASSWORD = 'password123';
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

       // -- Users
        $aliceUser = new User()
            ->setEmail('alice@example.fr')
            ->setCreatedAt(new DateTimeImmutable());

        $password = $this->hasher->hashPassword($aliceUser, self::PLAIN_PASSWORD);
        $aliceUser->setPassword($password);

        $manager->persist($aliceUser);


        $bobUser = new User()
            ->setEmail('bob@example.fr')
            ->setCreatedAt(new DateTimeImmutable());

        $password = $this->hasher->hashPassword($bobUser, self::PLAIN_PASSWORD);
        $bobUser->setPassword($password);
        $manager->persist($bobUser);

        $camilleUser = new User()
            ->setFirstName('Camille')
            ->setLastName("Aubert")
            ->setEmail('camille.aubert@example.fr')
            ->setCreatedAt(new DateTimeImmutable("2026-02-04T09:00:00"));

        $password = $this->hasher->hashPassword($camilleUser, self::PLAIN_PASSWORD);
        $camilleUser->setPassword($password);
        $manager->persist($camilleUser);

        $manager->flush();
    }
}
