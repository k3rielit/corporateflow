<?php

namespace Modules\Clubcard\Tests;

use Modules\Clubcard\Models\Clubcard;
use Tests\TestCase;

class ClubcardGenerationTest extends TestCase
{

    public function test_registration(): void
    {
        $model = Clubcard::factory()->randomEmail()->randomPassword()->generate()->create();
        $login = $model->login();
        $this->assertNotEmpty($login->accessToken);
        $this->assertNotEmpty($login->refreshToken);
    }

}
