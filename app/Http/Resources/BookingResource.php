<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'total' => $this->total,
            'balance' => $this->balance,
            'status' => $this->status->value,
            'status_label' => $this->status->name,
            'hotspot_username' => $this->hotspot_username,
            'hotspot_password' => $this->hotspot_password,
            'finances' => FinanceResource::collection($this->whenLoaded('finances')),
            'events' => BookingEventResource::collection($this->whenLoaded('events')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}