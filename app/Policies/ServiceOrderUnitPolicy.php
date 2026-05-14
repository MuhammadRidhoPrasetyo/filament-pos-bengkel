<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ServiceOrderUnit;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceOrderUnitPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ServiceOrderUnit');
    }

    public function view(AuthUser $authUser, ServiceOrderUnit $serviceOrderUnit): bool
    {
        return $authUser->can('View:ServiceOrderUnit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ServiceOrderUnit');
    }

    public function update(AuthUser $authUser, ServiceOrderUnit $serviceOrderUnit): bool
    {
        return $authUser->can('Update:ServiceOrderUnit');
    }

    public function delete(AuthUser $authUser, ServiceOrderUnit $serviceOrderUnit): bool
    {
        return $authUser->can('Delete:ServiceOrderUnit');
    }

    public function restore(AuthUser $authUser, ServiceOrderUnit $serviceOrderUnit): bool
    {
        return $authUser->can('Restore:ServiceOrderUnit');
    }

    public function forceDelete(AuthUser $authUser, ServiceOrderUnit $serviceOrderUnit): bool
    {
        return $authUser->can('ForceDelete:ServiceOrderUnit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ServiceOrderUnit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ServiceOrderUnit');
    }

    public function replicate(AuthUser $authUser, ServiceOrderUnit $serviceOrderUnit): bool
    {
        return $authUser->can('Replicate:ServiceOrderUnit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ServiceOrderUnit');
    }

}