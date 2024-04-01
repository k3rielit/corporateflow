<?php

namespace Modules\Clubcard\Enums;

use App\EnumHelperTrait;

enum RegistrationMcaRemoteStatusEnum: string
{
    use EnumHelperTrait;

    case Eligible = 'eligible';
    case Activated = 'activated';
}
