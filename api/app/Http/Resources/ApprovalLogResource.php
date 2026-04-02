<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'actor'     => $this->user->name ?? 'Unknown',
            'role'      => $this->role,
            'action'    => $this->action,
            'notes'     => $this->notes,
            'created_at'=> $this->created_at,
        ];
    }
}
