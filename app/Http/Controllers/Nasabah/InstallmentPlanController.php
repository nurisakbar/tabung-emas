<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\GoldInstallmentPlan;
use App\Models\GoldPrice;
use App\Services\InstallmentPlanService;
use App\Services\InstallmentCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentPlanController extends Controller
{
    protected InstallmentPlanService $planService;
    protected InstallmentCalculatorService $calculatorService;

    public function __construct(
        InstallmentPlanService $planService,
        InstallmentCalculatorService $calculatorService
    ) {
        $this->planService = $planService;
        $this->calculatorService = $calculatorService;
    }

    /**
     * Display a listing of installment plans
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->installmentPlans()->with(['schedules', 'payments'])->latest();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $plans = $query->paginate(10);

        return view('nasabah.installments.index', compact('plans'));
    }

    /**
     * Show the form for creating a new installment plan
     */
    public function create()
    {
        $latestPrice = GoldPrice::latest('date')->first();
        
        if (!$latestPrice) {
            return redirect()->route('nasabah.installments.index')
                ->with('error', 'Harga emas belum tersedia. Silakan hubungi admin.');
        }

        return view('nasabah.installments.create', compact('latestPrice'));
    }

    /**
     * Calculate installment preview (AJAX)
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'gold_amount' => ['required', 'numeric', 'min:0.0001'],
            'tenor' => ['required', 'integer', 'in:3,6,12,24'],
            'frequency' => ['required', 'in:monthly,weekly'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
        ]);

        $latestPrice = GoldPrice::latest('date')->first();
        if (!$latestPrice) {
            return response()->json(['error' => 'Harga emas belum tersedia'], 400);
        }

        $simulation = $this->calculatorService->simulate([
            'gold_amount' => $request->gold_amount,
            'price_per_gram' => $latestPrice->buy_price,
            'tenor' => $request->tenor,
            'frequency' => $request->frequency,
            'admin_fee' => 0, // Can be configured later
            'down_payment' => $request->down_payment ?? 0,
            'start_date' => now(),
        ]);

        return response()->json($simulation);
    }

    /**
     * Store a newly created installment plan
     */
    public function store(Request $request)
    {
        $request->validate([
            'gold_amount' => ['required', 'numeric', 'min:0.0001'],
            'tenor' => ['required', 'integer', 'in:3,6,12,24'],
            'frequency' => ['required', 'in:monthly,weekly'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $latestPrice = GoldPrice::latest('date')->first();

        if (!$latestPrice) {
            return redirect()->route('nasabah.installments.create')
                ->with('error', 'Harga emas belum tersedia.');
        }

        try {
            $plan = $this->planService->createPlan($user, [
                'gold_amount' => $request->gold_amount,
                'price_per_gram' => $latestPrice->buy_price,
                'tenor' => $request->tenor,
                'frequency' => $request->frequency,
                'down_payment' => $request->down_payment ?? 0,
                'admin_fee' => 0, // Can be configured later
                'notes' => $request->notes,
            ]);

            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('success', 'Paket cicilan emas berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->route('nasabah.installments.create')
                ->with('error', 'Terjadi kesalahan saat membuat paket cicilan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified installment plan
     */
    public function show($id)
    {
        $user = auth()->user();
        $plan = $user->installmentPlans()
            ->with(['schedules' => function($query) {
                $query->orderBy('due_date');
            }, 'payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        $nextSchedule = $plan->schedules()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();

        return view('nasabah.installments.show', compact('plan', 'nextSchedule'));
    }

    /**
     * Show the form for making a payment
     */
    public function payment($id)
    {
        $user = auth()->user();
        $plan = $user->installmentPlans()
            ->with(['schedules' => function($query) {
                $query->where('status', 'pending')->orderBy('due_date');
            }])
            ->findOrFail($id);

        $nextSchedule = $plan->schedules()->first();

        if (!$nextSchedule) {
            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('error', 'Tidak ada cicilan yang perlu dibayar.');
        }

        return view('nasabah.installments.payment', compact('plan', 'nextSchedule'));
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:transfer,cash,other'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'schedule_id' => ['nullable', 'exists:installment_schedules,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $plan = $user->installmentPlans()->findOrFail($id);

        try {
            $payment = $this->planService->processPayment($plan, [
                'payment_amount' => $request->payment_amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'schedule_id' => $request->schedule_id,
                'notes' => $request->notes,
                'auto_verify' => false, // Admin needs to verify
            ]);

            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('success', 'Pembayaran berhasil dikirim. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            return redirect()->route('nasabah.installments.payment', $plan->id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for early payment
     */
    public function earlyPayment($id)
    {
        $user = auth()->user();
        $plan = $user->installmentPlans()->findOrFail($id);

        if ($plan->isCompleted()) {
            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('error', 'Paket cicilan sudah lunas.');
        }

        $remainingAmount = $plan->getRemainingAmount();

        return view('nasabah.installments.early-payment', compact('plan', 'remainingAmount'));
    }

    /**
     * Process early payment
     */
    public function processEarlyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:transfer,cash,other'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $plan = $user->installmentPlans()->findOrFail($id);

        if ($plan->isCompleted()) {
            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('error', 'Paket cicilan sudah lunas.');
        }

        try {
            $payment = $this->planService->processEarlyPayment($plan, [
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'auto_verify' => false, // Admin needs to verify
            ]);

            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('success', 'Pelunasan lebih cepat berhasil dikirim. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            return redirect()->route('nasabah.installments.early-payment', $plan->id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show cancel form
     */
    public function cancel($id)
    {
        $user = auth()->user();
        $plan = $user->installmentPlans()->findOrFail($id);

        if (!$plan->canBeCancelled()) {
            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('error', 'Paket cicilan tidak dapat dibatalkan.');
        }

        return view('nasabah.installments.cancel', compact('plan'));
    }

    /**
     * Process cancellation
     */
    public function processCancel(Request $request, $id)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $plan = $user->installmentPlans()->findOrFail($id);

        try {
            $this->planService->cancelPlan($plan, $request->reason);

            return redirect()->route('nasabah.installments.index')
                ->with('success', 'Paket cicilan berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->route('nasabah.installments.show', $plan->id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
