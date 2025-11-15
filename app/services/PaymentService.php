<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Client\RequestException;

class PaymentService
{
    protected string $url;

    public function __construct()
    {
        $this->url = env('PAYMENT_URL', 'https://reqres.in/api/payments');
    }

    public function process(float $amount): array
    {
        $response = Http::timeout(5)
            ->withOptions([
                'verify' => app()->environment('local') ? false : true,
            ])
            ->post($this->url,[
                'amount' => $amount,
            ]);

            if($response->failed()) {
                return [
                    'success' => false,
                    'transaction_id' => null,
                    'payload' => $response->json(),
                ];
            }

            return [
                'success' => true,
                'transaction_id' => $response->json('id') ?? null,
                'payload' => $response->json(),
            ];

    }
}
