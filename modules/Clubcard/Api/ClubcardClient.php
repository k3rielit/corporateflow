<?php

namespace Modules\Clubcard\Api;

use Illuminate\Support\Str;
use Modules\Clubcard\Dto\ClubcardPersonalDataDto;
use Modules\Clubcard\Models\Clubcard;

class ClubcardClient
{
    protected ClubcardApi $api;
    protected string $email = '';
    protected string $password = '';

    public function __construct()
    {
        $this->api = ClubcardApi::make();
        $this->password = Str::password(12);
    }

    public static function make(): static
    {
        return new static();
    }

    public function email(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function password(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function generate(): Clubcard
    {
        return new Clubcard();
    }

}
