<?php

namespace App\Dto\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
class UserProfilePictureInput
{

    public function __construct(
        #[Assert\NotNull]
        #[Assert\Image(
            maxSize: '2M',
            mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        )]
        public ?UploadedFile $file = null,
    ) {
    }
}
