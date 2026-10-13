<?php

namespace App\Models;

use App\Enums\Privilege;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property UserRole $role
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'phone', 'password', 'role'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'role' => UserRole::class,
        ];
    }

    /**
     * Determine if user is a Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    /**
     * Determine if user has the admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin || $this->role === UserRole::SuperAdmin;
    }

    /**
     * Determine if user is an Editor.
     */
    public function isEditor(): bool
    {
        return $this->role === UserRole::Editor;
    }

    /**
     * Determine if user is a Customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === UserRole::Customer;
    }

    /**
     * Check if user matches a given role.
     */
    public function hasRole(UserRole|string $role): bool
    {
        $roleEnum = is_string($role) ? UserRole::tryFrom($role) : $role;

        return $this->role === $roleEnum;
    }

    /**
     * Check if user has a given privilege.
     */
    public function hasPrivilege(Privilege|string $privilege): bool
    {
        return $this->role?->hasPrivilege($privilege) ?? false;
    }

    /**
     * Get all privileges for the user.
     *
     * @return array<int, Privilege>
     */
    public function privileges(): array
    {
        return $this->role?->privileges() ?? [];
    }

    /**
     * Get user addresses.
     *
     * @return HasMany<Address, $this>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get user orders.
     *
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user reviews.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get user wishlists.
     *
     * @return HasMany<Wishlist, $this>
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get user cart.
     *
     * @return HasOne<Cart, $this>
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get user coupon usages.
     *
     * @return HasMany<CouponUsage, $this>
     */
    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }
}
