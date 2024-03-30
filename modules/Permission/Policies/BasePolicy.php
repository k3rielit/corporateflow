<?php

namespace Modules\Permission\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Permission\Dto\ModelPermissionDto;

class BasePolicy
{
    protected ?string $model = Model::class;
    protected ModelPermissionDto $permissions;

    public function __construct()
    {
        $this->permissions = ModelPermissionDto::fromClass($this->model);
    }

}
