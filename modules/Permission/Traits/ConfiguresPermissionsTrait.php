<?php

namespace Modules\Permission\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Permission\Database\Factories\PermissionFactory;
use Modules\Permission\Database\Factories\RoleFactory;
use Modules\Permission\Dto\ModelPermissionDto;
use Spatie\Permission\Models\Role;

trait ConfiguresPermissionsTrait
{

    public function emptyCache(): void
    {
        Artisan::call('optimize:clear');
    }

    /**
     * Delete all currently existing roles, and create new ones according to the configuration.
     * @return void
     */
    public function resetRoles(): void
    {
        $table = config('permission.table_names.roles', 'roles');
        DB::table($table)->delete();
        // Get the array of configured roles
        $roles = array_keys(config('role-permissions'));
        // Persist roles
        foreach ($roles as $role) {
            RoleFactory::new()->state(['name' => $role, 'guard_name' => 'web'])->create();
        }
    }

    /**
     * Delete all currently existing permissions, and create new ones according to the configuration.
     * @return void
     */
    public function resetPermissions(): void
    {
        $table = config('permission.table_names.permissions', 'permissions');
        DB::table($table)->delete();
        $config = config('role-permissions');
        $permissions = [];
        foreach ($config as $role => $permissionConfig) {
            // Normal permission strings
            $rolePermissions = $permissionConfig['permissions'] ?? [];
            $rolePermissions = array_combine($rolePermissions, $rolePermissions);
            $permissions = array_merge($permissions, $rolePermissions);
            // Model permission strings
            $modelPermissions = $permissionConfig['model_permissions'] ?? [];
            foreach ($modelPermissions as $model => $actions) {
                $generated = ModelPermissionDto::fromClass($model)->toArray();
                $generated = array_combine($generated, $generated);
                $permissions = array_merge($permissions, $generated);
            }
        }
        // Persist permissions
        foreach ($permissions as $permission) {
            PermissionFactory::new()->state(['name' => $permission])->create();
        }
    }

    /**
     * Delete all user - role associations from the database, and create new ones based on the configuration.
     * @return void
     */
    public function resetUserRoles(): void
    {
        $table = config('permission.table_names.model_has_roles', 'model_has_roles');
        DB::table($table)->delete();
        $config = config('user-roles');
        $users = User::query()->whereIn('email', array_keys($config))->get()->mapWithKeys(fn(User $item) => [$item?->email => $item]);
        foreach ($users as $email => $user) {
            $roles = $config[$email] ?? [];
            $user->syncRoles($roles);
        }
    }

    public function resetRolePermissions(): void
    {
        $table = config('permission.table_names.role_has_permissions', 'role_has_permissions');
        DB::table($table)->delete();
        $config = config('role-permissions');
        foreach ($config as $role => $permissionConfig) {
            $roleModel = Role::findByName($role);
            $rolePermissions = $permissionConfig['permissions'] ?? [];
            $roleModel->syncPermissions($rolePermissions);
            $modelPermissions = $permissionConfig['model_permissions'] ?? [];
            foreach ($modelPermissions as $model => $actions) {
                $dto = ModelPermissionDto::fromClass($model)->toArray();
                foreach ($dto as $action => $permission) {
                    if (in_array($action, $actions)) {
                        $roleModel->givePermissionTo($permission);
                    }
                }
            }
        }
    }

    public function resetEverything(): void
    {
        $this->emptyCache();
        $this->resetPermissions();
        $this->resetRoles();
        $this->resetRolePermissions();
        $this->resetUserRoles();
    }

}
