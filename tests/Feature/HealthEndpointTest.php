<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthEndpointTest extends TestCase
{
    public function test_health_endpoint_returns_lightweight_ok_response(): void
    {
        $this->getJson('/health')
            ->assertOk()
            ->assertHeaderMissing('Set-Cookie')
            ->assertExactJson([
                'status' => 'ok',
                'app' => 'Fitness Exercise Manager',
            ]);
    }
}
