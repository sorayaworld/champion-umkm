<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmortizationScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'installment_number' => $this->installment_number,
            'principal_payment'  => (float) $this->principal_payment,
            'interest_payment'   => (float) $this->interest_payment,
            'total_payment'      => (float) $this->total_payment,
            'remaining_balance'  => (float) $this->remaining_balance,
            'due_date'           => $this->due_date,
        ];
    }
}
