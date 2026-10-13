<?php

namespace App\Enums;

enum ShippingMethodType: string
{
    case FlatRate = 'flat_rate';
    case FreeShipping = 'free_shipping';
    case LocalPickup = 'local_pickup';
    case WeightBased = 'weight_based';
}
