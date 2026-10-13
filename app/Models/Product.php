<?php

namespace App\Models;

use App\Enums\ProductType;
use App\Enums\StockStatus;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'type',
    'name',
    'slug',
    'sku',
    'short_description',
    'description',
    'price',
    'sale_price',
    'sale_starts_at',
    'sale_ends_at',
    'manage_stock',
    'stock_qty',
    'stock_status',
    'weight',
    'length',
    'width',
    'height',
    'brand_id',
    'is_featured',
    'is_active',
    'meta_title',
    'meta_description',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'stock_status' => StockStatus::class,
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'sale_starts_at' => 'datetime',
            'sale_ends_at' => 'datetime',
            'manage_stock' => 'boolean',
            'stock_qty' => 'integer',
            'weight' => 'decimal:2',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if product is variable.
     */
    public function isVariable(): bool
    {
        return $this->type === ProductType::Variable;
    }

    /**
     * Check if product is simple.
     */
    public function isSimple(): bool
    {
        return $this->type === ProductType::Simple;
    }

    /**
     * Get the brand of the product.
     *
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the categories for the product.
     *
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * Get the images for the product.
     *
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the attributes for the product.
     *
     * @return BelongsToMany<Attribute, $this>
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('is_variation', 'is_visible')
            ->withTimestamps();
    }

    /**
     * Get the attribute values selected for this product.
     *
     * @return BelongsToMany<AttributeValue, $this>
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values');
    }

    /**
     * Get the product variations.
     *
     * @return HasMany<ProductVariation, $this>
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get the tags for the product.
     *
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    /**
     * Get the reviews for the product.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the wishlists containing this product.
     *
     * @return HasMany<Wishlist, $this>
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the order items for the product.
     *
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the stock movements for the product.
     *
     * @return HasMany<StockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
