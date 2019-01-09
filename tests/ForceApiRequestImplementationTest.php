<?php

declare(strict_types=1);

namespace Sarala;

class ForceApiRequestImplementationTest extends TestCase
{
    public function test_it_force_to_implement_ApiRequestAbstract()
    {
        $this->apiRequest('get', route('non-api-request'))
            ->assertStatus(400)
            ->assertJson([
                'error' => [
                    'status' => '400',
                    'title' => 'API Request should extend Sarala\\Http\\Requests\\ApiRequestAbstract',
                ],
            ]);
    }
}
