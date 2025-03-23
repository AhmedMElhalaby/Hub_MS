<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'expense' => new ExpenseResource($this->whenLoaded('expense')),
            'amount' => $this->amount,
            'note' => $this->note,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}