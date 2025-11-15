<?php
namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentProcessTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_payment_update()
    {
        Http::fake([
            '*'=> Http::response(['id' => 'tx_123'],200),
        ]);

        $order = Order::factory()->create([
            'amount' => 120.00,
            'status' => 'pending',
        ]);

        $response = $this->postJson("/api/v1/orders/{$order->id}/payments");

        $response->assertStatus(200)
                ->assertJsonPath('data.success', true);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'amount' => 120.00,
            'success' => 1,
        ]);
    }

    public function test_failed_payment_sets_order_failed_and_retry()
    {
        Http::fakeSequence()
            ->push(['error' => 'declined'], 400)
            ->push(['id' => 'tx_123'], 200);

        $order = Order::factory()->create([
            'amount' => 500.00,
            'status' => 'pending',
        ]);

        $resp1 = $this->postJson("/api/v1/orders/{$order->id}/payments");

        $resp1->assertStatus(200)
                ->assertJsonPath('data.success', false);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'failed',
        ]);

        $resp2 = $this->postJson("/api/v1/orders/{$order->id}/payments");

        $resp2->assertStatus(200)
            ->assertJsonPath('data.success', true);

        $this->assertDatabaseHas('orders',[
            'id' => $order->id,
            'status' => 'paid',
        ]);
    }
}
