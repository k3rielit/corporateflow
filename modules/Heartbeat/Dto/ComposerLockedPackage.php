<?php

namespace Modules\Heartbeat\Dto;

class ComposerLockedPackage
{

    public string|null $name = null;
    public string|null $version = null;
    public string|null $source = null;
    public string|null $dist = null;
    public string|null $description = null;

    public static function make(): static
    {
        return new static();
    }

    // Setters

    public static function fromBlock(array $block): static
    {
        $sourceBlock = $block['source'] ?? [];
        $distBlock = $block['dist'] ?? [];
        return static::make()
            ->name($block['name'] ?? null)
            ->version($block['version'] ?? null)
            ->source($sourceBlock['url'] ?? null)
            ->dist($distBlock['url'] ?? null)
            ->description($block['description'] ?? null);
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

    public function source(string|null $source): static
    {
        $this->source = $source;
        return $this;
    }

    public function dist(string|null $dist): static
    {
        $this->dist = $dist;
        return $this;
    }

    public function description(string|null $description): static
    {
        $this->description = $description;
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

    public function getSource(): string|null
    {
        return $this->source;
    }

    public function getDist(): string|null
    {
        return $this->dist;
    }

    public function getDescription(): string|null
    {
        return $this->description;
    }

}
