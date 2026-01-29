<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldInstallmentPlan;
use App\Models\InstallmentPayment;
use App\Services\InstallmentPlanService;
use Illuminate\Http\Request;

class InstallmentPlanController extends Controller
{
    protected InstallmentPlanService $planService;

    public function __construct(InstallmentPlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * Display a listing of all installment plans
     */
    public function index(Request $request)
    {
        $query = GoldInstallmentPlan::with(['user', 'schedules', 'payments'])->latest();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by user name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('start_date', '<=', $request->date_to);
        }

        $plans = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => GoldInstallmentPlan::count(),
            'active' => GoldInstallmentPlan::where('status', 'active')->count(),
            'overdue' => GoldInstallmentPlan::where('status', 'overdue')->count(),
            'completed' => GoldInstallmentPlan::where('status', 'completed')->count(),
            'pending_verification' => InstallmentPayment::where('status', 'pending')->count(),
        ];

        return view('admin.installments.index', compact('plans', 'stats'));
    }

    /**
     * Display the specified installment plan
     */
    public function show($id)
    {
        $plan = GoldInstallmentPlan::with([
            'user',
            'schedules' => function($query) {
                $query->orderBy('due_date');
            },
            'payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'payments.verifier'
        ])->findOrFail($id);

        return view('admin.installments.show', compact('plan'));
    }

    /**
     * Verify a payment
     */
    public function verifyPayment(Request $request, $paymentId)
    {
        $payment = InstallmentPayment::with('installmentPlan')->findOrFail($paymentId);

        if ($payment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pembayaran ini sudah diverifikasi atau ditolak.');
        }

        try {
            $this->planService->verifyPayment($payment, auth()->id());

            return redirect()->route('admin.installments.show', $payment->installment_plan_id)
                ->with('success', 'Pembayaran berhasil diverifikasi dan emas telah dialokasikan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject a payment
     */
    public function rejectPayment(Request $request, $paymentId)
    {
        $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        $payment = InstallmentPayment::findOrFail($paymentId);

        if ($payment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pembayaran ini sudah diverifikasi atau ditolak.');
        }

        try {
            $payment->reject(auth()->id(), $request->notes);

            return redirect()->route('admin.installments.show', $payment->installment_plan_id)
                ->with('success', 'Pembayaran ditolak.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
