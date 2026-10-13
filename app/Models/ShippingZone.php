<?php

namespace App\Models;

use Database\Factories\ShippingZoneFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'sort_order', 'is_active'])]
class ShippingZone extends Model
{
    /** @use HasFactory<ShippingZoneFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get locations for the zone.
     *
     * @return HasMany<ShippingZoneLocation, $this>
     */
    public function locations(): HasMany
    {
        return $this->hasMany(ShippingZoneLocation::class, 'shipping_zone_id');
    }

    /**
     * Get shipping methods for the zone.
     *
     * @return HasMany<ShippingMethod, $this>
     */
    public function methods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class, 'shipping_zone_id')->orderBy('sort_order');
    }
}
