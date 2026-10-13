<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Editor = 'editor';
    case Customer = 'customer';

    /**
     * Get label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Editor => 'Editor',
            self::Customer => 'Customer',
        };
    }

    /**
     * Get default privileges for this role.
     *
     * @return array<int, Privilege>
     */
    public function privileges(): array
    {
        return match ($this) {
            self::SuperAdmin => Privilege::cases(),
            self::Admin => [
                Privilege::ViewProducts,
                Privilege::CreateProducts,
                Privilege::EditProducts,
                Privilege::DeleteProducts,
                Privilege::ManageCategories,
                Privilege::ManageBrands,
                Privilege::ManageAttributes,
                Privilege::ManageTags,
                Privilege::ViewOrders,
                Privilege::EditOrders,
                Privilege::ManageOrderStatus,
                Privilege::ViewPayments,
                Privilege::ManageRefunds,
                Privilege::ViewUsers,
                Privilege::CreateUsers,
                Privilege::EditUsers,
                Privilege::ManageCoupons,
                Privilege::ManageShipping,
                Privilege::ManageReviews,
                Privilege::ManageSettings,
                Privilege::ViewReports,
                Privilege::PlaceOrders,
                Privilege::CreateReviews,
            ],
            self::Editor => [
                Privilege::ViewProducts,
                Privilege::CreateProducts,
                Privilege::EditProducts,
                Privilege::DeleteProducts,
                Privilege::ManageCategories,
                Privilege::ManageBrands,
                Privilege::ManageAttributes,
                Privilege::ManageTags,
                Privilege::ViewOrders,
                Privilege::ManageReviews,
                Privilege::CreateReviews,
            ],
            self::Customer => [
                Privilege::ViewProducts,
                Privilege::PlaceOrders,
                Privilege::CreateReviews,
            ],
        };
    }

    /**
     * Check if the role has a given privilege.
     */
    public function hasPrivilege(Privilege|string $privilege): bool
    {
        $privilegeEnum = is_string($privilege) ? Privilege::tryFrom($privilege) : $privilege;

        if (! $privilegeEnum) {
            return false;
        }

        if ($this === self::SuperAdmin) {
            return true;
        }

        return in_array($privilegeEnum, $this->privileges(), true);
    }
}
