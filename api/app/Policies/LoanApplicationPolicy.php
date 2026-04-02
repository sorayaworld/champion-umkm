<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LoanApplicationPolicy
{

    public function review(User $user, LoanApplication $loanApplication): bool
    {
        return $user->role === 'officer' && $loanApplication->status === 'submitted';
    }


    public function approve(User $user, LoanApplication $loanApplication): bool
    {
        return $user->role === 'manager' && $loanApplication->status === 'reviewed';
    }


    public function reject(User $user, LoanApplication $loanApplication): bool
    {
        return $user->role === 'manager' && $loanApplication->status === 'reviewed';
    }
}
