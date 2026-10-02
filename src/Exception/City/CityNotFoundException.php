<?php

namespace App\Exception\City;

use Symfony\Component\HttpFoundation\Response;

class CityNotFoundException extends \RuntimeException
{
    /**
     * Signals a lookup on a city identifier that matches nothing.
     */
    public function __construct()
    {
        // le message ne porte pas l'identifiant : il finirait dans le journal
        parent::__construct('Ville non trouvée', Response::HTTP_NOT_FOUND);
    }
}
