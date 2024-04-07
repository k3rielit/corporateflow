<?php

namespace Modules\Clubcard\Policies;

use App\Models\User;
use Modules\Clubcard\Models\Clubcard;
use Modules\Permission\Policies\BasePolicy;

class ClubcardPolicy extends BasePolicy
{
    protected ?string $model = Clubcard::class;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can($this->permissions->viewAny);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Clubcard $clubcard): bool
    {
        return $user->can($this->permissions->view);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can($this->permissions->create);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Clubcard $clubcard): bool
    {
        return $user->can($this->permissions->update);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Clubcard $clubcard): bool
    {
        return $user->can($this->permissions->delete);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Clubcard $clubcard): bool
    {
        return $user->can($this->permissions->restore);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Clubcard $clubcard): bool
    {
        return $user->can($this->permissions->forceDelete);
    }

}
