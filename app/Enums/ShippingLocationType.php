<?php

namespace App\Enums;

enum ShippingLocationType: string
{
    case Country = 'country';
    case State = 'state';
    case City = 'city';
    case Postcode = 'postcode';
}
