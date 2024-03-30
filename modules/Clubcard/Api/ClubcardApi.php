<?php

namespace Modules\Clubcard\Api;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Collection;

class ClubcardApi
{
    protected Collection $userAgents;
    protected Collection $countryCodes;
    protected Guzzle $client;

    public function __construct()
    {
        $this->userAgents = collect(config('clubcard.user_agents'));
        $this->countryCodes = collect(config('clubcard.country_codes'));
        $this->client = new Guzzle([
            'base_uri' => config('clubcard.base_uri'),
        ]);
    }

    // User agent

    public function userAgent(string $userAgent): static
    {
        $this->userAgents = collect([$userAgent]);
        return $this;
    }

    public function userAgents(Collection|array $userAgents): static
    {
        $this->userAgents = is_array($userAgents) ? collect($userAgents) : $userAgents;
        return $this;
    }

    public function pushUserAgents(Collection|array $userAgents): static
    {
        $this->userAgents->push(...$userAgents);
        return $this;
    }

    public function getUserAgents(): Collection
    {
        return $this->userAgents;
    }

    public function getUserAgent(): ?string
    {
        try {
            return $this->userAgents->random();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    // Requests


}
