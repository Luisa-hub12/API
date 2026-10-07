<?php

namespace App\Entity\Enum;

enum CartPaymentMethod: string
{
    case CARD = 'card';
    case VOUCHER = 'voucher';

}
