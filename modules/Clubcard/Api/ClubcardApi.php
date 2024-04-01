<?php

namespace Modules\Clubcard\Api;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ClubcardApi
{
    protected Collection $userAgents;
    protected Collection $countryCodes;
    protected Guzzle $client;

    public function __construct(?string $baseUri = null)
    {
        $this->userAgents = collect(config('clubcard.user_agents'));
        $this->countryCodes = collect(config('clubcard.country_codes'));
        $this->client = new Guzzle([
            'base_uri' => $baseUri ?? config('clubcard.base_uri'),
        ]);
    }

    public static function make(?string $baseUri = null): static
    {
        return new static($baseUri);
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

    // Country code

    public function countryCode(string $countryCode): static
    {
        $this->countryCodes = collect([$countryCode]);
        return $this;
    }

    public function countryCodes(Collection|array $countryCodes): static
    {
        $this->countryCodes = is_array($countryCodes) ? collect($countryCodes) : $countryCodes;
        return $this;
    }

    public function pushCountryCodes(Collection|array $countryCodes): static
    {
        $this->countryCodes->push(...$countryCodes);
        return $this;
    }

    public function getCountryCodes(): Collection
    {
        return $this->countryCodes;
    }

    public function getCountryCode(): ?string
    {
        try {
            return $this->countryCodes->random();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    // Headers

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Accept' => 'application/json',
            'DeviceUUID' => $this->getDeviceUUID(),
            'User-Agent' => $this->getUserAgent(),
            'Country-Code' => $this->getCountryCode(),
        ];
    }

    // Requests - General

    public function getDeviceUUID(): ?string
    {
        $request = new GuzzleRequest('GET', '/v3/devices/uuid');
        $response = $this->client->send($request);
        $content = $response->getBody()->getContents();
        return json_decode($content)->device_uuid;
    }

    // Requests - Registration

    public function registrationIghsCheck(string $email, string $password): ?string
    {
        $body = json_encode([
            'email' => $email,
            'password' => $password,
        ]);
        $request = new GuzzleRequest('POST', '/v3/registrations/ighs/check', $this->getHeaders(), $body);
        $response = $this->client->send($request);
        $content = $response->getBody()->getContents();
        return json_decode($content)->status;
    }

}
