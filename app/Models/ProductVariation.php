<?php

namespace App\Models;

use App\Enums\StockStatus;
use Database\Factories\ProductVariationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'product_id',
    'sku',
    'price',
    'sale_price',
    'manage_stock',
    'stock_qty',
    'stock_status',
    'weight',
    'image',
    'is_active',
])]
class ProductVariation extends Model
{
    /** @use HasFactory<ProductVariationFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'manage_stock' => 'boolean',
            'stock_qty' => 'integer',
            'stock_status' => StockStatus::class,
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the product that owns this variation.
     *
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute values that define this variation.
     *
     * @return BelongsToMany<AttributeValue, $this>
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'variation_attribute_values', 'variation_id', 'attribute_value_id');
    }

    /**
     * Get variation attribute values pivot entries.
     *
     * @return HasMany<VariationAttributeValue, $this>
     */
    public function variationAttributeValues(): HasMany
    {
        return $this->hasMany(VariationAttributeValue::class, 'variation_id');
    }

    /**
     * Get order items for this variation.
     *
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variation_id');
    }

    /**
     * Get cart items for this variation.
     *
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'variation_id');
    }

    /**
     * Get stock movements for this variation.
     *
     * @return HasMany<StockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'variation_id');
    }
}
