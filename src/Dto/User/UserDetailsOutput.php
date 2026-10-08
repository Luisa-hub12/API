<?php

namespace App\Dto\User;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeImmutable;

class UserDetailsOutput{

    // Un identifiant
    // une adresse électronique
    // un nom et un prénom
    // une date d'ouverture
    public function __construct(

        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "L'identifiant de l'utilisateur",
            "format" => "uuid",
            "example" => "d290f1ee-6c54-4b01-90e6-d701748f0851"
        ])]
        public string $id,
        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "L'email de l'utilisateur",
            "format" => "email",
            "example" => "user@gmail.com"
        ])]
        public string $email,
        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "Le prénom de l'utilisateur",
            "example" => "John",
            "nullable" => true,
        ])]
        public null|string $firstName = null,
        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "Le nom de l'utilisateur",
            "example" => "Doe",
            "nullable" => true,
        ])]
        public null|string $lastName = null,
        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "La date de création de l'utilisateur",
            "format" => "date-time",
            "example" => "2023-07-12T14:30:00+00:00"
        ])]
        public DateTimeImmutable $createdAt,

        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "L'URL de la photo de profil de l'utilisateur",
            "format" => "uri",
            "nullable" => true,
        ])]
        public null|string $profilePictureUrl,
    ){}
}
