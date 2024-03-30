<?php

namespace Modules\Permission\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Modules\Permission\Traits\ConfiguresPermissionsTrait;
use Spatie\Permission\Models\Permission;

class ResetPermissionsCommand extends Command
{
    use ConfiguresPermissionsTrait;

    protected $signature = 'permissions:reset';

    protected $description = 'Resets every role and permission.';

    public function handle()
    {
        $shouldContinue = $this->ask('Are you sure? (y/n)', 'n') === 'y';
        if (!$shouldContinue) {
            return;
        }

        $this->resetEverything();
        $tableRows = [];
        $config = config('user-roles');
        $users = User::query()->whereIn('email', array_keys($config))->get()->mapWithKeys(fn(User $item) => [$item?->email => $item]);
        foreach ($users as $email => $user) {
            $roles = $user->getRoleNames();
            $permissions = $user->getAllPermissions()->map(fn(Permission $item) => $item->name);
            $tableRows[] = [
                $email,
                $roles,
                $permissions,
            ];
        }
        $this->table(['User', 'Roles', 'Permissions'], $tableRows);
    }

}
