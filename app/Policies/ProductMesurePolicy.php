<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductMesure;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductMesurePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_product::mesure');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductMesure  $productMesure
     * @return bool
     */
    public function view(User $user, ProductMesure $productMesure): bool
    {
        return $user->can('view_product::mesure');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_product::mesure');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductMesure  $productMesure
     * @return bool
     */
    public function update(User $user, ProductMesure $productMesure): bool
    {
        return $user->can('update_product::mesure');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductMesure  $productMesure
     * @return bool
     */
    public function delete(User $user, ProductMesure $productMesure): bool
    {
        return $user->can('delete_product::mesure');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_product::mesure');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductMesure  $productMesure
     * @return bool
     */
    public function forceDelete(User $user, ProductMesure $productMesure): bool
    {
        return $user->can('force_delete_product::mesure');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_product::mesure');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductMesure  $productMesure
     * @return bool
     */
    public function restore(User $user, ProductMesure $productMesure): bool
    {
        return $user->can('restore_product::mesure');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_product::mesure');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductMesure  $productMesure
     * @return bool
     */
    public function replicate(User $user, ProductMesure $productMesure): bool
    {
        return $user->can('replicate_product::mesure');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_product::mesure');
    }

}
