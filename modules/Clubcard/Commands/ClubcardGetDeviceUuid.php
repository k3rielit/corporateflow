<?php

namespace Modules\Clubcard\Commands;

use Illuminate\Console\Command;
use Modules\Clubcard\Api\ClubcardApi;

class ClubcardGetDeviceUuid extends Command
{
    protected $signature = 'clubcard:device-uuid';

    protected $description = 'Requests and displays a new DeviceUUID.';

    public function handle()
    {
        $uuid = ClubcardApi::make()->getDeviceUUID();
        $this->info($uuid);
    }

}
