<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\UserPanelProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Modules\Barcode\Providers\BarcodeServiceProvider::class,
    Modules\Clubcard\Providers\ClubcardServiceProvider::class,
    Modules\Permission\Providers\PermissionServiceProvider::class,
    Modules\Heartbeat\Providers\HeartbeatServiceProvider::class,
];
