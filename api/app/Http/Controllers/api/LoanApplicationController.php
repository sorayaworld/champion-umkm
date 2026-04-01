<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanApplicationRequest;
use App\Http\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use App\Services\AmortizationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanApplicationController extends Controller
{
    use ApiResponse;

    protected $amortizationService;

    public function __construct(AmortizationService $amortizationService)
    {
        $this->amortizationService = $amortizationService;
    }

    public function index(Request $request)
    {
        $profile = $request->user()->umkmProfile;

        if (!$profile) {
            return $this->success([], 'No applications found (No UMKM Profile)');
        }

        $applications = $profile->loanApplications()->latest()->get();

        return $this->success(LoanApplicationResource::collection($applications), 'Loan application list');
    }

    public function store(StoreLoanApplicationRequest $request)
    {
        $user = $request->user();
        $profile = $user->umkmProfile;

        if (!$profile) {
            return $this->error('Anda harus melengkapi profil UMKM sebelum mengajukan pinjaman.', null, 422);
        }

        $amount = (float) $request->amount;
        $maxLoan = (float) $profile->omzet * 6;

        return DB::transaction(function () use ($request, $profile, $amount, $maxLoan) {
            $interestRate = $this->amortizationService->calculateInterestRate($profile);

            $loan = $profile->loanApplications()->create(array_merge($request->validated(), [
                'interest_rate' => $interestRate,
                'status'        => $amount > $maxLoan ? 'rejected' : 'submitted',
            ]));

            if ($loan->status === 'submitted') {
                $this->amortizationService->generateSchedule($loan);
            }

            $message = $loan->status === 'rejected' 
                ? 'Pengajuan ditolak otomatis karena melebihi batas maksimal (6x omzet).' 
                : 'Pengajuan pinjaman berhasil dikirim.';

            return $this->success(new LoanApplicationResource($loan), $message, 201);
        });
    }

    public function show(Request $request, $id)
    {
        $profile = $request->user()->umkmProfile;

        if (!$profile) {
            return $this->error('UMKM Profile tidak ditemukan.', null, 404);
        }

        $loan = $profile->loanApplications()->with(['amortizationSchedules', 'approvalLogs'])->where('id', $id)->firstOrFail();

        return $this->success(new LoanApplicationResource($loan), 'Loan application detail');
    }
}
