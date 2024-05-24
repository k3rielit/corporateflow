<?php

namespace Modules\Heartbeat\Dto;

class ComposerRequiredPackage
{

    public string|null $name = null;
    public string|null $version = null;

    public static function make(): static
    {
        return new static();
    }

    // Setters

    public static function fromBlock(array $block): static
    {
        return static::make()
            ->name($block['name'] ?? null)
            ->version($block['version'] ?? null);
    }

    public function name(string|null $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function version(string|null $version): static
    {
        $this->version = $version;
        return $this;
    }

    // Getters

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getVersion(): string|null
    {
        return $this->version;
    }

}
