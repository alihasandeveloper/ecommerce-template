<?php

namespace App\Models;

use Database\Factories\AttributeValueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'attribute_id',
    'value',
    'slug',
    'color_code',
    'sort_order',
])]
class AttributeValue extends Model
{
    /** @use HasFactory<AttributeValueFactory> */
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
        ];
    }

    /**
     * Get the attribute that owns this value.
     *
     * @return BelongsTo<Attribute, $this>
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the products linked to this attribute value.
     *
     * @return BelongsToMany<Product, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values');
    }

    /**
     * Get variations using this attribute value.
     *
     * @return BelongsToMany<ProductVariation, $this>
     */
    public function variations(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariation::class, 'variation_attribute_values', 'attribute_value_id', 'variation_id');
    }
}
