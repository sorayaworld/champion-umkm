<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function stats(Request $request)
    {
        $user = $request->user();
        
        $query = LoanApplication::query();
        
        if ($user->role === 'borrower') {
            $profile = $user->umkmProfile;
            if (!$profile) {
                return $this->success([
                    'total_submission'    => 0,
                    'total_reviewed'      => 0,
                    'total_approved'      => 0,
                    'total_rejected'      => 0,
                    'approval_ratio'      => 0,
                    'average_loan_amount' => 0,
                ], 'Dashboard stats');
            }
            $query->where('umkm_profile_id', $profile->id);
        }

        $totalSubmission = (clone $query)->count();
        $totalReviewed   = (clone $query)->where('status', 'reviewed')->count();
        $totalApproved   = (clone $query)->where('status', 'approved')->count();
        $totalRejected   = (clone $query)->where('status', 'rejected')->count();

        $approvalRatio = $totalSubmission > 0 ? round(($totalApproved / $totalSubmission) * 100, 2) : 0;
        $averageLoanAmount = $totalSubmission > 0 ? (clone $query)->avg('amount') : 0;

        return $this->success([
            'total_submission'    => $totalSubmission,
            'total_reviewed'      => $totalReviewed,
            'total_approved'      => $totalApproved,
            'total_rejected'      => $totalRejected,
            'approval_ratio'      => $approvalRatio,
            'average_loan_amount' => round((float)$averageLoanAmount, 2),
        ], 'Dashboard stats');
    }
}
