<?php

namespace Modules\Clubcard\Enums;

use App\EnumHelperTrait;

enum RegistrationIghsCheckEnum: string
{
    use EnumHelperTrait;

    case New = 'new';
    case Active = 'active';
}
