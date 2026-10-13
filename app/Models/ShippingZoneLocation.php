<?php

namespace App\Models;

use App\Enums\ShippingLocationType;
use Database\Factories\ShippingZoneLocationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'shipping_zone_id',
    'type',
    'code',
])]
class ShippingZoneLocation extends Model
{
    /** @use HasFactory<ShippingZoneLocationFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'type' => ShippingLocationType::class,
        ];
    }

    /**
     * Get the shipping zone.
     *
     * @return BelongsTo<ShippingZone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }
}
