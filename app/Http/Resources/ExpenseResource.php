<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category->value,
            'category_label' => $this->category->name,
            'amount' => $this->amount,
            'finances' => FinanceResource::collection($this->whenLoaded('finances')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}