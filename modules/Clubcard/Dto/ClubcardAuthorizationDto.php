<?php

namespace Modules\Clubcard\Dto;

class ClubcardAuthorizationDto
{

    public ?string $accessToken = null;
    public ?string $refreshToken = null;

    public function __construct(?string $accessToken, ?string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public static function make(?string $accessToken, ?string $refreshToken): static
    {
        return new static($accessToken, $refreshToken);
    }

    public static function fromJson(string $content): static
    {
        $object = json_decode($content);
        return new static($object->accessToken, $object->refreshToken);
    }

}
