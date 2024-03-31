<?php

namespace Modules\Clubcard\Tests;

use Modules\Clubcard\Api\ClubcardApi;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ClubcardEndpointTest extends TestCase
{
    protected ClubcardApi $api;

    public function setUp(): void
    {
        parent::setUp();
        $this->api = new ClubcardApi();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_device_uuid(): void
    {
        $deviceUuid = $this->api->getDeviceUUID();
        $validator = Validator::make(
            ['device_uuid' => $deviceUuid],
            ['device_uuid' => 'required|uuid']
        );
        $this->assertTrue($validator->passes());
    }

}
