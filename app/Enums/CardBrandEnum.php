<?php

namespace App\Enums;

enum CreditCardTypeEnum: string
{
    case VISA = 'visa';
    case MASTERCARD = 'mastercard';
    case AMERICAN_EXPRESS = 'american_express';
}
