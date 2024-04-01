<?php

namespace App;

trait EnumHelperTrait
{

    /**
     * Return the enum cases as an array of name => value pairs.
     * @return array
     */
    public static function getCases(): array
    {
        $cases = static::cases();
        return array_column($cases, 'value', 'name');
    }

}
