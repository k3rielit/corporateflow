<?php

namespace Modules\Heartbeat\Dto;

use Illuminate\Support\Collection;

class ComposerInformation
{

    public string $configurationPath;
    public string $lockfilePath;
    public bool $configurationExists = false;
    public bool $lockfileExists = false;

    public Collection $require;
    public Collection $requireDev;
    public Collection $locked;
    public Collection $lockedDev;

    public function __construct()
    {
        $this->configurationPath = base_path('composer.json');
        $this->lockfilePath = base_path('composer.lock');
        $this->configurationExists = is_file($this->configurationPath);
        $this->lockfileExists = is_file($this->lockfilePath);
        $this->require = collect();
        $this->requireDev = collect();
        $this->locked = collect();
        $this->lockedDev = collect();
    }

    public static function make(): static
    {
        return new static();
    }

    public function parseConfiguration(): array
    {
        if (!$this->configurationExists) {
            return [];
        }
        $config = file_get_contents($this->configurationPath);
        return json_decode($config ?: '[]', true);
    }

    public function parseLockfile(): array
    {
        if (!$this->lockfileExists) {
            return [];
        }
        $lock = file_get_contents($this->lockfilePath);
        return json_decode($lock ?: '[]', true);
    }

    public function configuration(): static
    {
        $config = $this->parseConfiguration();
        $this->require = collect($config['require'] ?? [])
            ->map(fn(string $version, string $package) => ComposerRequiredPackage::make()->version($version)->name($package));
        $this->requireDev = collect($config['require-dev'] ?? [])
            ->map(fn(string $version, string $package) => ComposerRequiredPackage::make()->version($version)->name($package));
        return $this;
    }

    public function lockfile(): static
    {
        $lock = $this->parseLockfile();
        $this->locked = collect($lock['packages'] ?? [])->map(fn(array $item) => ComposerLockedPackage::fromBlock($item));
        $this->lockedDev = collect($lock['packages-dev'] ?? [])->map(fn(array $item) => ComposerLockedPackage::fromBlock($item));
        return $this;
    }

}
