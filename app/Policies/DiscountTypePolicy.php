<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DiscountType;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DiscountType');
    }

    public function view(AuthUser $authUser, DiscountType $discountType): bool
    {
        return $authUser->can('View:DiscountType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DiscountType');
    }

    public function update(AuthUser $authUser, DiscountType $discountType): bool
    {
        return $authUser->can('Update:DiscountType');
    }

    public function delete(AuthUser $authUser, DiscountType $discountType): bool
    {
        return $authUser->can('Delete:DiscountType');
    }

    public function restore(AuthUser $authUser, DiscountType $discountType): bool
    {
        return $authUser->can('Restore:DiscountType');
    }

    public function forceDelete(AuthUser $authUser, DiscountType $discountType): bool
    {
        return $authUser->can('ForceDelete:DiscountType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DiscountType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DiscountType');
    }

    public function replicate(AuthUser $authUser, DiscountType $discountType): bool
    {
        return $authUser->can('Replicate:DiscountType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DiscountType');
    }

}