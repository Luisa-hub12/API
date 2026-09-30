<?php

namespace App\Dto\User;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterInput
{

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(min: 3, max: 255)]
        #[ApiProperty(required: true, schema: [
            'type' => 'string',
            'format' => 'email',
            'description' => 'L\email de l\utilisateur (doit être unique)',
            'minLength' => 3,
            'maxLength' => 255,
            'example' => 'user@example.com',
        ])]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\PasswordStrength]
        #[Assert\Length(min: 8, max: 255)]#[ApiProperty(required: true, schema: [
            'type' => 'string',
            'description' => 'Le mot de passe de l\utilisateur (doit être sécurisé)',
            'format' => 'password',
            'minLength' => 8,
            'maxLength' => 255,
            'example' => 'MonSuperMotDePasse',
        ]
        )]
        public string $password,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min: 3, max: 255)]
        #[ApiProperty(required: false, schema: [
            'type' => 'string',
            'description' => 'Le prénom de l\utilisateur',
            'minLength' => 3,
            'maxLength' => 255,
        ]
        )]
        public null|string $firstName = null,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min: 3, max: 255)]
        #[ApiProperty(required: false, schema: [
            'type' => 'string',
            'format' => 'email',
            'description' => 'Prénom de l\utilisateur',
            'minLength' => 3,
            'maxLength' => 255,
        ]
        )]
        public null|string $lastName = null,
    )
    {

    }

}
