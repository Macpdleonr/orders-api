<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('payments')->orderBy('created_at', 'desc')->get();
        return OrderResource::collection($orders);
    }

    public function store (OrderRequest $request): JsonResponse
    {
        $data = $request->validated();

        $order = Order::create([
            'name' => $data['name'],
            'amount' => $data['amount'],
            'status' => 'pending',
        ]);

        return (new OrderResource($order->load('payments')))
            ->response()
            ->setStatusCode(201);
    }

    public function show (Order $order)
    {
        return new OrderResource($order->load('payments'));
    }
}
