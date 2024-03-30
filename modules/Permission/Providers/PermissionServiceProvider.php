<?php

namespace Modules\Permission\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Permission\Commands\ResetPermissionsCommand;

class PermissionServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Configuration/roles.php', 'role-permissions');
        $this->mergeConfigFrom(__DIR__ . '/../Configuration/users.php', 'user-roles');
        $this->commands([
            ResetPermissionsCommand::class,
        ]);
    }

}
