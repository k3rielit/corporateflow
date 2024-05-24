<?php

namespace Modules\Heartbeat\Dto;

use RuntimeException;

class CpuUsage
{

    public int $usage = 0;

    public static function make(): static
    {
        return new static();
    }

    public function init(): static
    {
        $this->usage = match (PHP_OS_FAMILY) {
            'Darwin' => (int)shell_exec('top -l 1 | grep -E "^CPU" | tail -1 | awk \'{ print $3 + $5 }\''),
            'Linux' => (int)shell_exec('top -bn1 | grep -E \'^(%Cpu|CPU)\' | awk \'{ print $2 + $4 }\''),
            'Windows' => (int)trim(shell_exec('wmic cpu get loadpercentage | more +1') ?? ''),
            'BSD' => (int)shell_exec('top -b -d 2| grep \'CPU: \' | tail -1 | awk \'{print$10}\' | grep -Eo \'[0-9]+\.[0-9]+\' | awk \'{ print 100 - $1 }\''),
            default => throw new RuntimeException('The pulse:check command does not currently support ' . PHP_OS_FAMILY),
        };
        return $this;
    }

}
