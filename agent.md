# Laravel Ecommerce – Database Structure

WooCommerce-style, kintu simple ebong functional ekta ecommerce database design (Laravel + MySQL).

## Table of Contents

1. [Overview](#overview)
2. [Users & Addresses](#1-users--addresses)
3. [Categories & Brands](#2-categories--brands)
4. [Products](#3-products)
5. [Attributes & Variable Products](#4-attributes--variable-products)
6. [Cart & Coupons](#5-cart--coupons)
7. [Shipping](#6-shipping)
8. [Orders & Payments](#7-orders--payments)
9. [Checkout Flow](#8-checkout-flow)
10. [Extra Tables](#9-extra-tables)
11. [Relationships](#relationships)
12. [Payment Gateways (Bangladesh)](#payment-gateways-bangladesh)
13. [Best Practices](#best-practices)
14. [Suggested Build Order](#suggested-build-order)

---

## Overview

| Module | Tables |
|---|---|
| Users | `users`, `addresses` |
| Catalog | `categories`, `brands`, `products`, `category_product`, `product_images` |
| Attributes / Variations | `attributes`, `attribute_values`, `product_attributes`, `product_attribute_values`, `product_variations`, `variation_attribute_values` |
| Cart & Coupon | `carts`, `cart_items`, `coupons`, `coupon_usages` |
| Shipping | `shipping_zones`, `shipping_zone_locations`, `shipping_methods` |
| Orders | `orders`, `order_items`, `order_status_histories`, `payments`, `payment_methods` |
| Extra | `reviews`, `wishlists`, `settings`, `stock_movements`, `tags`, `product_tag` |

---

## 1. Users & Addresses

### `users` (Laravel default + extra)

| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| name | string | |
| email | string, unique | |
| phone | string, nullable | |
| password | string | |
| role | enum(`super_admin`,`admin`,`editor`,`customer`) | default `customer` |
| timestamps | | |

#### Roles & Privileges
- **`super_admin`**: Full system access (all privileges).
- **`admin`**: Catalog, orders, payments, coupons, shipping, reviews, reports, users management.
- **`editor`**: Catalog management (products, categories, brands, attributes, tags), view orders, manage reviews.
- **`customer`**: Storefront access (view products, place orders, create reviews).


### `addresses`

| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | |
| type | enum(`billing`,`shipping`) | |
| name | string | |
| phone | string | |
| address_line1 | string | |
| address_line2 | string, nullable | |
| city | string | |
| state | string, nullable | |
| postcode | string, nullable | |
| country | string(2) | default `BD` |
| is_default | boolean | |

---

## 2. Categories & Brands

### `categories`

| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| parent_id | FK → categories, nullable | nested category |
| name | string | |
| slug | string, unique | |
| image | string, nullable | |
| description | text, nullable | |
| sort_order | int | default 0 |
| is_active | boolean | |
| meta_title, meta_description | string, nullable | SEO |

### `brands` (optional)

`id, name, slug (unique), logo, is_active`

---

## 3. Products

### `products`

| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| type | enum(`simple`,`variable`) | |
| name | string | |
| slug | string, unique | |
| sku | string, unique, nullable | |
| short_description | text, nullable | |
| description | longtext, nullable | |
| price | decimal(12,2) | simple product-er price |
| sale_price | decimal(12,2), nullable | |
| sale_starts_at / sale_ends_at | datetime, nullable | |
| manage_stock | boolean | |
| stock_qty | int | |
| stock_status | enum(`in_stock`,`out_of_stock`,`backorder`) | |
| weight, length, width, height | decimal, nullable | |
| brand_id | FK → brands, nullable | |
| is_featured | boolean | |
| is_active | boolean | |
| meta_title, meta_description | string, nullable | SEO |
| deleted_at | softDeletes | |

> **Simple product**: price/stock `products` table-e thake.
> **Variable product**: price/stock `product_variations` table-e thake. Parent-er price shudhu "From ৳xxx" display-er jonno.

### `category_product` (pivot)

`product_id, category_id`

### `product_images`

`id, product_id, path, alt, sort_order, is_primary`

---

## 4. Attributes & Variable Products

### `attributes` (Color, Size ...)

`id, name, slug, type (select/color/button), is_filterable`

### `attribute_values` (Red, Blue, S, M ...)

`id, attribute_id, value, slug, color_code (nullable), sort_order`

### `product_attributes`

Kon product-e kon attribute use hoise.

`id, product_id, attribute_id, is_variation (bool), is_visible (bool)`

### `product_attribute_values`

Product-e kon kon value select kora.

`product_id, attribute_value_id`

### `product_variations`

`id, product_id, sku, price, sale_price, manage_stock, stock_qty, stock_status, weight, image, is_active`

### `variation_attribute_values`

Ekta variation = attribute value-er combination (e.g. Red + M).

`id, variation_id, attribute_id, attribute_value_id`

**Example: T-Shirt (variable)**

| Variation | Color | Size | Price | Stock |
|---|---|---|---|---|
| #1 | Red | M | 500 | 10 |
| #2 | Blue | L | 550 | 5 |

---

## 5. Cart & Coupons

### `carts`

`id, user_id (nullable), session_id (nullable), timestamps`

### `cart_items`

`id, cart_id, product_id, variation_id (nullable), quantity`

### `coupons`

| Column | Type |
|---|---|
| id | PK |
| code | string, unique |
| type | enum(`percent`,`fixed`,`free_shipping`) |
| value | decimal(12,2) |
| min_order_amount | decimal, nullable |
| max_discount | decimal, nullable |
| usage_limit | int, nullable |
| usage_limit_per_user | int, nullable |
| used_count | int |
| starts_at, expires_at | datetime, nullable |
| is_active | boolean |

### `coupon_usages`

`id, coupon_id, user_id, order_id`

---

## 6. Shipping

### `shipping_zones`

`id, name, sort_order, is_active`  (e.g. *Dhaka City*, *Outside Dhaka*)

### `shipping_zone_locations`

`id, zone_id, type (country/state/city/postcode), code`

### `shipping_methods`

`id, zone_id, title, type (flat_rate/free_shipping/local_pickup/weight_based), cost, min_order_amount, settings (json), sort_order, is_active`

**Bangladesh-er example setup**

| Zone | Method | Cost |
|---|---|---|
| Inside Dhaka | Flat Rate | 60৳ |
| Outside Dhaka | Flat Rate | 120৳ |
| All | Free Shipping | order 3000৳+ hole |

---

## 7. Orders & Payments

### `orders`

| Group | Columns |
|---|---|
| Basic | `id, order_number (unique), user_id (nullable – guest checkout), status, currency` |
| Totals | `subtotal, discount_total, shipping_total, tax_total, grand_total` |
| Coupon | `coupon_id, coupon_code` |
| Shipping | `shipping_method_id, shipping_method_title` |
| Payment | `payment_method, payment_status (unpaid/paid/failed/refunded)` |
| Billing snapshot | `billing_name, billing_phone, billing_email, billing_address, billing_city, billing_state, billing_postcode, billing_country` |
| Shipping snapshot | `shipping_name, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_postcode, shipping_country` |
| Misc | `customer_note, ip_address, paid_at, timestamps` |

**Order status:** `pending → processing → on_hold → completed / cancelled / refunded`

### `order_items`

`id, order_id, product_id, variation_id (nullable), name, sku, variation_data (json), price, quantity, subtotal, total`

### `order_status_histories`

`id, order_id, user_id, status, note, is_customer_notified, timestamps`

### `payments`

`id, order_id, gateway (cod/bkash/nagad/sslcommerz/aamarpay), transaction_id, amount, currency, status (pending/paid/failed/refunded), payload (json), paid_at`

### `payment_methods` (admin panel theke on/off-er jonno)

`id, code, title, description, settings (json), sort_order, is_active`

> **Important – Snapshot:** Order-e address, product name, SKU, price **copy kore rakhte hobe**. Na hole pore product price/address change korle purono order-er data bhul hoye jabe.

---

## 8. Checkout Flow

Checkout-er jonno alada table lage na. Eta ekta flow:

1. Cart theke items load kora
2. Billing / Shipping address neowa
3. Shipping zone detect kore shipping method select
4. Coupon apply
5. Payment method select
6. `DB::transaction` er moddhe:
   - `orders` create
   - `order_items` create
   - `payments` create
   - stock minus
   - coupon `used_count` + `coupon_usages`
   - cart clear

---

## 9. Extra Tables

| Table | Columns |
|---|---|
| `reviews` | `id, product_id, user_id, rating, comment, is_approved` |
| `wishlists` | `id, user_id, product_id` |
| `settings` | `key, value` (store name, currency, tax rate ...) |
| `stock_movements` | `id, product_id, variation_id, qty_change, reason, order_id` |
| `tags` | `id, name, slug` |
| `product_tag` | `product_id, tag_id` |

---

## Relationships

```
Category      ⇄ Product            (many-to-many via category_product)
Product       → hasMany            Images, Variations
Product       ⇄ Attributes         (via product_attributes)
Variation     ⇄ AttributeValues    (via variation_attribute_values)
Cart          → hasMany            CartItems
Order         → hasMany            OrderItems, Payments, StatusHistories
Order         → belongsTo          User, ShippingMethod, Coupon
ShippingZone  → hasMany            Locations, Methods
User          → hasMany            Addresses, Orders, Reviews, Wishlists
```

---

## Payment Gateways (Bangladesh)

Laravel nijer theke kono gateway support kore na, API/package use korte hoy.

| Gateway | Note |
|---|---|
| **COD** | API lage na, sobcheye common |
| **bKash** | Direct merchant API (Tokenized Checkout) |
| **Nagad** | Direct merchant API |
| **SSLCommerz** | Aggregator: Card, bKash, Nagad, Rocket, Upay, Banking |
| **aamarPay** | Aggregator, setup shohoj |
| **ShurjoPay** | Local aggregator |

**Recommendation:** Shuru-te **COD + SSLCommerz/aamarPay**. Order beshi hole bKash/Nagad direct.

API key/secret `.env` file-e rakho, database-e plain text-e na.

---

## Best Practices

- Price column-e `decimal(12,2)` use koro, `float` na.
- `slug`, `sku`, `order_number`, `coupon code`-e **unique index** dao.
- `products`-e `softDeletes` rakho, jate purono order-er reference na bhange.
- Stock update-er somoy `DB::transaction` + `lockForUpdate()` use koro.
- Foreign key-e index dao; `orders.status`, `orders.user_id`, `products.is_active`-eo index dao.
- Order-e address ar item data snapshot hishabe store koro.

---

## Suggested Build Order

1. `users`, `addresses`
2. `categories`, `products`, `category_product`, `product_images`
3. `shipping_zones`, `shipping_zone_locations`, `shipping_methods`
4. `carts`, `cart_items`
5. `orders`, `order_items`, `payments`, `order_status_histories`
6. `coupons`, `coupon_usages`
7. `attributes`, `attribute_values`, `product_attributes`, `product_attribute_values`, `product_variations`, `variation_attribute_values`
8. `reviews`, `wishlists`, `settings`, `stock_movements`

> Prothome simple product + order + shipping diye MVP banao, pore variable product add koro.