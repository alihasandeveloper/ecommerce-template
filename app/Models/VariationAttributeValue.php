<?php

namespace App\Models;

use Database\Factories\VariationAttributeValueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'variation_id',
    'attribute_id',
    'attribute_value_id',
])]
class VariationAttributeValue extends Model
{
    /** @use HasFactory<VariationAttributeValueFactory> */
    use HasFactory;

    /**
     * Get the variation.
     *
     * @return BelongsTo<ProductVariation, $this>
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    /**
     * Get the attribute.
     *
     * @return BelongsTo<Attribute, $this>
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the attribute value.
     *
     * @return BelongsTo<AttributeValue, $this>
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
