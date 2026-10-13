<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case Cod = 'cod';
    case Bkash = 'bkash';
    case Nagad = 'nagad';
    case Sslcommerz = 'sslcommerz';
    case Aamarpay = 'aamarpay';
}
