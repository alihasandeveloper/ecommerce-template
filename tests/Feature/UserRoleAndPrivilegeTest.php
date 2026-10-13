<?php

use App\Enums\Privilege;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('super admin has all privileges and passes gate checks', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    expect($superAdmin->role)->toBe(UserRole::SuperAdmin)
        ->and($superAdmin->isSuperAdmin())->toBeTrue()
        ->and($superAdmin->isAdmin())->toBeTrue()
        ->and($superAdmin->hasPrivilege(Privilege::DeleteUsers))->toBeTrue()
        ->and($superAdmin->hasPrivilege(Privilege::ManageSettings))->toBeTrue()
        ->and($superAdmin->hasPrivilege(Privilege::CreateProducts))->toBeTrue();

    expect(Gate::forUser($superAdmin)->allows('products.create'))->toBeTrue()
        ->and(Gate::forUser($superAdmin)->allows('settings.manage'))->toBeTrue()
        ->and(Gate::forUser($superAdmin)->allows('users.delete'))->toBeTrue();
});

test('admin has administrative privileges but cannot delete super admin tasks', function () {
    $admin = User::factory()->admin()->create();

    expect($admin->role)->toBe(UserRole::Admin)
        ->and($admin->isSuperAdmin())->toBeFalse()
        ->and($admin->isAdmin())->toBeTrue()
        ->and($admin->hasPrivilege(Privilege::CreateProducts))->toBeTrue()
        ->and($admin->hasPrivilege(Privilege::ManageCoupons))->toBeTrue()
        ->and($admin->hasPrivilege(Privilege::ManageSettings))->toBeTrue();

    expect(Gate::forUser($admin)->allows('products.create'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('settings.manage'))->toBeTrue();
});

test('editor can manage products and catalog but cannot manage settings or users', function () {
    $editor = User::factory()->editor()->create();

    expect($editor->role)->toBe(UserRole::Editor)
        ->and($editor->isEditor())->toBeTrue()
        ->and($editor->isAdmin())->toBeFalse()
        ->and($editor->hasPrivilege(Privilege::CreateProducts))->toBeTrue()
        ->and($editor->hasPrivilege(Privilege::ManageCategories))->toBeTrue()
        ->and($editor->hasPrivilege(Privilege::ManageSettings))->toBeFalse()
        ->and($editor->hasPrivilege(Privilege::DeleteUsers))->toBeFalse();

    expect(Gate::forUser($editor)->allows('products.create'))->toBeTrue()
        ->and(Gate::forUser($editor)->allows('categories.manage'))->toBeTrue()
        ->and(Gate::forUser($editor)->allows('settings.manage'))->toBeFalse()
        ->and(Gate::forUser($editor)->allows('users.delete'))->toBeFalse();
});

test('customer only has customer storefront privileges', function () {
    $customer = User::factory()->customer()->create();

    expect($customer->role)->toBe(UserRole::Customer)
        ->and($customer->isCustomer())->toBeTrue()
        ->and($customer->isAdmin())->toBeFalse()
        ->and($customer->isEditor())->toBeFalse()
        ->and($customer->hasPrivilege(Privilege::ViewProducts))->toBeTrue()
        ->and($customer->hasPrivilege(Privilege::PlaceOrders))->toBeTrue()
        ->and($customer->hasPrivilege(Privilege::CreateReviews))->toBeTrue()
        ->and($customer->hasPrivilege(Privilege::CreateProducts))->toBeFalse()
        ->and($customer->hasPrivilege(Privilege::ManageSettings))->toBeFalse();

    expect(Gate::forUser($customer)->allows('products.view'))->toBeTrue()
        ->and(Gate::forUser($customer)->allows('orders.place'))->toBeTrue()
        ->and(Gate::forUser($customer)->allows('products.create'))->toBeFalse()
        ->and(Gate::forUser($customer)->allows('settings.manage'))->toBeFalse();
});
