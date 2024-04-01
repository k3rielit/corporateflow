<?php

namespace Modules\Clubcard\Tests;

use Modules\Clubcard\Api\ClubcardApi;
use Modules\Clubcard\Enums\RegistrationIghsCheckEnum;
use Modules\Clubcard\Enums\RegistrationMcaRemoteStatusEnum;
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

    public function test_header_merging(): void
    {
        $mergeHeaders = [
            'DeviceUUID' => '3e2a5ce0-f019-11ee-ae3d-f9ed3fce8ff2',
            'Accept' => '*/*',
        ];
        $headers = $this->api->getHeaders($mergeHeaders);
        foreach ($mergeHeaders as $header => $value) {
            $this->assertArrayHasKey($header, $headers, "Header '{$header} => {$value}' was not included: " . json_encode($headers));
            $this->assertEquals($value, $headers[$header] ?? null, "Header '{$header} => {$value}' overriding failed: " . json_encode($headers));
        }
    }

    public function test_device_uuid(): void
    {
        $deviceUuid = $this->api->getDeviceUUID();
        $validator = Validator::make(
            ['device_uuid' => $deviceUuid],
            ['device_uuid' => 'required|uuid']
        );
        $this->assertTrue($validator->passes(), "Failed to assert that " . ($deviceUuid ?? 'NULL') . " passes the required|uuid validation.");
    }

}
