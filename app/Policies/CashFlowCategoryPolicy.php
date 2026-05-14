<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CashFlowCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashFlowCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CashFlowCategory');
    }

    public function view(AuthUser $authUser, CashFlowCategory $cashFlowCategory): bool
    {
        return $authUser->can('View:CashFlowCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CashFlowCategory');
    }

    public function update(AuthUser $authUser, CashFlowCategory $cashFlowCategory): bool
    {
        return $authUser->can('Update:CashFlowCategory');
    }

    public function delete(AuthUser $authUser, CashFlowCategory $cashFlowCategory): bool
    {
        return $authUser->can('Delete:CashFlowCategory');
    }

    public function restore(AuthUser $authUser, CashFlowCategory $cashFlowCategory): bool
    {
        return $authUser->can('Restore:CashFlowCategory');
    }

    public function forceDelete(AuthUser $authUser, CashFlowCategory $cashFlowCategory): bool
    {
        return $authUser->can('ForceDelete:CashFlowCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CashFlowCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CashFlowCategory');
    }

    public function replicate(AuthUser $authUser, CashFlowCategory $cashFlowCategory): bool
    {
        return $authUser->can('Replicate:CashFlowCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CashFlowCategory');
    }

}