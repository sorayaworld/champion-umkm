<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'business_name'      => $this->umkmProfile->business_name ?? null,
            'amount'              => (float) $this->amount,
            'tenor'               => (int) $this->tenor,
            'interest_rate'       => (float) $this->interest_rate,
            'monthly_installment' => (float) $this->monthly_installment,
            'total_interest'      => (float) $this->total_interest,
            'total_payment'       => (float) $this->total_payment,
            'status'              => $this->status,
            'purpose'             => $this->purpose,
            'description'         => $this->description,
            'created_at'          => $this->created_at,
        ];
    }
}
