<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store (PaymentRequest $request, Order $order): JsonResponse
    {

        $order->refresh();

        if (!in_array($order->status, ['pending', 'failed'])) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'This order was paid.'
                ]
            ], 400);
        }


        $amount = (float) $order->amount;

        return DB::transaction(function () use ($order, $amount) {

            $result = $this-> paymentService->process($amount);

            $payment = $order->payments()->create([
                'amount'=> $amount,
                'success'=> $result['success'],
                'external_transaction_id'=> $result['transaction_id'] ?? null,
                'response_payload'=> $result['payload'] ?? null,
            ]);

            if ($result['success']) {
                $order->markAsPaid();
            } else {
                $order->markAsFailed();
            }

            return (new PaymentResource($payment->refresh()))
                ->response()
                ->setStatusCode(200);
        });
    }
}
