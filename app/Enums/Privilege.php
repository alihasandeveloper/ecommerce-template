<?php

namespace App\Enums;

enum Privilege: string
{
    // Products & Catalog
    case ViewProducts = 'products.view';
    case CreateProducts = 'products.create';
    case EditProducts = 'products.edit';
    case DeleteProducts = 'products.delete';

    // Categories & Brands & Attributes
    case ManageCategories = 'categories.manage';
    case ManageBrands = 'brands.manage';
    case ManageAttributes = 'attributes.manage';
    case ManageTags = 'tags.manage';

    // Orders & Sales
    case ViewOrders = 'orders.view';
    case EditOrders = 'orders.edit';
    case DeleteOrders = 'orders.delete';
    case ManageOrderStatus = 'orders.status.manage';

    // Payments & Refunds
    case ViewPayments = 'payments.view';
    case ManageRefunds = 'payments.refund';

    // Users & Customers
    case ViewUsers = 'users.view';
    case CreateUsers = 'users.create';
    case EditUsers = 'users.edit';
    case DeleteUsers = 'users.delete';

    // Marketing & Coupons
    case ManageCoupons = 'coupons.manage';

    // Shipping & Delivery
    case ManageShipping = 'shipping.manage';

    // Customer Reviews
    case ManageReviews = 'reviews.manage';

    // Store Settings & Analytics
    case ManageSettings = 'settings.manage';
    case ViewReports = 'reports.view';

    // Customer Actions
    case PlaceOrders = 'orders.place';
    case CreateReviews = 'reviews.create';

    /**
     * Get the human-readable group name.
     */
    public function group(): string
    {
        return match ($this) {
            self::ViewProducts,
            self::CreateProducts,
            self::EditProducts,
            self::DeleteProducts => 'Products',

            self::ManageCategories,
            self::ManageBrands,
            self::ManageAttributes,
            self::ManageTags => 'Catalog',

            self::ViewOrders,
            self::EditOrders,
            self::DeleteOrders,
            self::ManageOrderStatus,
            self::PlaceOrders => 'Orders',

            self::ViewPayments,
            self::ManageRefunds => 'Payments',

            self::ViewUsers,
            self::CreateUsers,
            self::EditUsers,
            self::DeleteUsers => 'Users',

            self::ManageCoupons => 'Coupons',
            self::ManageShipping => 'Shipping',
            self::ManageReviews,
            self::CreateReviews => 'Reviews',
            self::ManageSettings => 'Settings',
            self::ViewReports => 'Reports',
        };
    }

    /**
     * Get human-readable description/label.
     */
    public function label(): string
    {
        return match ($this) {
            self::ViewProducts => 'View Products',
            self::CreateProducts => 'Create Products',
            self::EditProducts => 'Edit Products',
            self::DeleteProducts => 'Delete Products',

            self::ManageCategories => 'Manage Categories',
            self::ManageBrands => 'Manage Brands',
            self::ManageAttributes => 'Manage Attributes',
            self::ManageTags => 'Manage Tags',

            self::ViewOrders => 'View Orders',
            self::EditOrders => 'Edit Orders',
            self::DeleteOrders => 'Delete Orders',
            self::ManageOrderStatus => 'Manage Order Status',
            self::PlaceOrders => 'Place Orders',

            self::ViewPayments => 'View Payments',
            self::ManageRefunds => 'Manage Refunds',

            self::ViewUsers => 'View Users',
            self::CreateUsers => 'Create Users',
            self::EditUsers => 'Edit Users',
            self::DeleteUsers => 'Delete Users',

            self::ManageCoupons => 'Manage Coupons',
            self::ManageShipping => 'Manage Shipping',
            self::ManageReviews => 'Manage Reviews',
            self::CreateReviews => 'Create Reviews',
            self::ManageSettings => 'Manage Settings',
            self::ViewReports => 'View Reports & Analytics',
        };
    }
}
