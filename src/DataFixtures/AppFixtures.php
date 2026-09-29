<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // le hacheur n'est pas construit ici, il est demandé au conteneur
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {
    }

    /**
     * Loads the three demo accounts the module works with.
     */
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTimeImmutable();

        // Alice et Bob n'ont ni prénom ni nom : les deux champs sont optionnels
        foreach (['alice@example.fr', 'bob@example.fr'] as $email) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, 'motdepasse'));
            $user->setCreatedAt($now);
            $manager->persist($user);
        }

        // Camille porte la parité avec les maquettes du module de conception
        $camille = new User();
        $camille->setEmail('camille.aubert@example.fr');
        $camille->setPassword($this->hasher->hashPassword($camille, 'motdepasse'));
        $camille->setFirstName('Camille');
        $camille->setLastName('Aubert');
        $camille->setCreatedAt(new \DateTimeImmutable('2026-02-04'));
        $manager->persist($camille);

        $cities = [
            'Paris',
            'Lyon',
            'Marseille',
            'Bordeaux',
            'Lille',
            'Strasbourg',
            'Toulouse',
            'Nantes',
            'Dijon',
            'Brest',
        ];

        foreach ($cities as $cityName) {
            $city = new City()
                ->setName($cityName)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($city);

        }

        $manager->flush();
    }
}
