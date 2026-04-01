<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmortizationSchedule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'loan_application_id',
        'installment_number',
        'principal_payment',
        'interest_payment',
        'total_payment',
        'remaining_balance',
        'due_date',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
