<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'umkm_profile_id',
        'amount',
        'tenor',
        'purpose',
        'description',
        'interest_rate',
        'monthly_installment',
        'total_interest',
        'total_payment',
        'status',
        'reviewed_by',
        'approved_by',
        'approved_at',
    ];
}
