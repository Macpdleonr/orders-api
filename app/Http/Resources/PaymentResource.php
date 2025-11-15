<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'amount' => (Float) $this->amount,
            'success' => $this->success,
            'external_transaction_id' => $this->external_transaction_id,
            'response_payload' => $this->response_payload,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
