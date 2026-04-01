<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UmkmProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'business_name'  => $this->business_name,
            'category'       => $this->category,
            'years_running'  => $this->years_running,
            'employee_count' => $this->employee_count,
            'address'        => $this->address,
            'city'           => $this->city,
            'province'       => $this->province,
            'omzet'          => (float) $this->omzet,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
