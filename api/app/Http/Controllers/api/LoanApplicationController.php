<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveApplicationRequest;
use App\Http\Requests\ReviewApplicationRequest;
use App\Http\Requests\StoreLoanApplicationRequest;
use App\Http\Resources\LoanApplicationResource;
use App\Models\ApprovalLog;
use App\Models\LoanApplication;
use App\Services\AmortizationService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanApplicationController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    protected $amortizationService;

    public function __construct(AmortizationService $amortizationService)
    {
        $this->amortizationService = $amortizationService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = LoanApplication::query()->with('umkmProfile');

        if ($user->role === 'borrower') {
            $profile = $user->umkmProfile;
            if (!$profile) return $this->success([], 'No applications found (No UMKM Profile)');
            $query->where('umkm_profile_id', $profile->id);
        }

        if ($request->filled('search')) {
            $query->whereHas('umkmProfile', function ($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $applications = $query->latest()->paginate($request->get('limit', 10));

        return response()->json([
            'success' => true,
            'message' => 'Loan application list',
            'data'    => LoanApplicationResource::collection($applications)->response()->getData(true)
        ]);
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
        $user = $request->user();
        
        if ($user->role === 'borrower') {
            $profile = $user->umkmProfile;
            if (!$profile) return $this->error('UMKM Profile tidak ditemukan.', null, 404);
            $loan = $profile->loanApplications();
        } else {
            $loan = LoanApplication::query();
        }

        $loan = $loan->with(['amortizationSchedules', 'approvalLogs'])->where('id', $id)->firstOrFail();

        return $this->success(new LoanApplicationResource($loan), 'Loan application detail');
    }

    public function review(ReviewApplicationRequest $request, $id)
    {
        $loan = LoanApplication::findOrFail($id);
        $this->authorize('review', $loan);

        return DB::transaction(function () use ($request, $loan) {
            $loan->update([
                'status'      => 'reviewed',
                'reviewed_by' => $request->user()->id,
            ]);

            $loan->approvalLogs()->create([
                'actor_id' => $request->user()->id,
                'role'     => $request->user()->role,
                'action'   => 'review',
                'notes'    => $request->notes,
            ]);

            return $this->success(new LoanApplicationResource($loan), 'Loan application reviewed.');
        });
    }

    public function approve(ApproveApplicationRequest $request, $id)
    {
        $loan = LoanApplication::findOrFail($id);
        $this->authorize('approve', $loan);

        return DB::transaction(function () use ($request, $loan) {
            $loan->update([
                'status'      => 'approved',
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);

            $loan->approvalLogs()->create([
                'actor_id' => $request->user()->id,
                'role'     => $request->user()->role,
                'action'   => 'approve',
                'notes'    => $request->notes,
            ]);

            return $this->success(new LoanApplicationResource($loan), 'Loan application approved.');
        });
    }

    public function reject(ApproveApplicationRequest $request, $id)
    {
        $loan = LoanApplication::findOrFail($id);
        $this->authorize('reject', $loan);

        return DB::transaction(function () use ($request, $loan) {
            $loan->update([
                'status' => 'rejected',
            ]);

            $loan->approvalLogs()->create([
                'actor_id' => $request->user()->id,
                'role'     => $request->user()->role,
                'action'   => 'reject',
                'notes'    => $request->notes,
            ]);

            return $this->success(new LoanApplicationResource($loan), 'Loan application rejected.');
        });
    }
}
