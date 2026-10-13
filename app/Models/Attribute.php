<?php

namespace App\Models;

use App\Enums\AttributeType;
use Database\Factories\AttributeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'type', 'is_filterable'])]
class Attribute extends Model
{
    /** @use HasFactory<AttributeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'type' => AttributeType::class,
            'is_filterable' => 'boolean',
        ];
    }

    /**
     * Get the values for the attribute.
     *
     * @return HasMany<AttributeValue, $this>
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order');
    }

    /**
     * Get the products that use this attribute.
     *
     * @return BelongsToMany<Product, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_attributes')
            ->withPivot('is_variation', 'is_visible')
            ->withTimestamps();
    }
}
