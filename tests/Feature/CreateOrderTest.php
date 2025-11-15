<?php

namespace App\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_successfully()
    {
        $payload = [
            'name' => 'Jose Test',
            'amount' => 130.45,
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201)
                ->assertJsonPath('data.name', 'Jose Test')
                ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('orders', [
            'name' => 'Jose Test',
            'amount' => 130.45,
            'status' => 'pending',
        ]);
    }
}
