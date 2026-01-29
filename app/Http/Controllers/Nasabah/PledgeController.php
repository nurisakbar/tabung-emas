<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\GoldPledge;
use App\Models\GoldPrice;
use App\Models\GoldSaving;
use App\Services\PledgeService;
use App\Services\PledgeCalculatorService;
use Illuminate\Http\Request;

class PledgeController extends Controller
{
    protected PledgeService $pledgeService;
    protected PledgeCalculatorService $calculatorService;

    public function __construct(
        PledgeService $pledgeService,
        PledgeCalculatorService $calculatorService
    ) {
        $this->pledgeService = $pledgeService;
        $this->calculatorService = $calculatorService;
    }

    /**
     * Display a listing of pledges
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->goldPledges()->with(['payments', 'extensions'])->latest();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $pledges = $query->paginate(10);

        return view('nasabah.pledges.index', compact('pledges'));
    }

    /**
     * Show the form for creating a new pledge
     */
    public function create()
    {
        $user = auth()->user();
        $goldSaving = $user->goldSaving;
        $latestPrice = GoldPrice::latest('date')->first();
        
        if (!$latestPrice) {
            return redirect()->route('nasabah.pledges.index')
                ->with('error', 'Harga emas belum tersedia. Silakan hubungi admin.');
        }

        if (!$goldSaving || $goldSaving->total_gold <= 0) {
            return redirect()->route('nasabah.pledges.index')
                ->with('error', 'Anda belum memiliki emas di tabungan. Silakan beli emas terlebih dahulu.');
        }

        return view('nasabah.pledges.create', compact('goldSaving', 'latestPrice'));
    }

    /**
     * Calculate pledge preview (AJAX)
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'gold_amount' => ['required', 'numeric', 'min:0.0001'],
            'loan_amount' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'in:1,3,6,12'],
        ]);

        $latestPrice = GoldPrice::latest('date')->first();
        if (!$latestPrice) {
            return response()->json(['error' => 'Harga emas belum tersedia'], 400);
        }

        $simulation = $this->calculatorService->simulate([
            'gold_amount' => $request->gold_amount,
            'sell_price' => $latestPrice->sell_price,
            'appraisal_rate' => 0.85,
            'loan_amount' => $request->loan_amount,
            'duration_months' => $request->duration_months,
            'admin_fee' => 50000,
            'storage_fee_rate' => 1.5,
        ]);

        return response()->json($simulation);
    }

    /**
     * Store a newly created pledge
     */
    public function store(Request $request)
    {
        $request->validate([
            'gold_amount' => ['required', 'numeric', 'min:0.0001'],
            'loan_amount' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'in:1,3,6,12'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $latestPrice = GoldPrice::latest('date')->first();

        if (!$latestPrice) {
            return redirect()->route('nasabah.pledges.create')
                ->with('error', 'Harga emas belum tersedia.');
        }

        try {
            $pledge = $this->pledgeService->createPledge($user, [
                'gold_amount' => $request->gold_amount,
                'sell_price' => $latestPrice->sell_price,
                'appraisal_rate' => 0.85,
                'loan_amount' => $request->loan_amount,
                'duration_months' => $request->duration_months,
                'admin_fee' => 50000,
                'storage_fee_rate' => 1.5,
                'notes' => $request->notes,
            ]);

            return redirect()->route('nasabah.pledges.show', $pledge->id)
                ->with('success', 'Pengajuan gadai emas berhasil dibuat! Menunggu persetujuan admin.');

        } catch (\Exception $e) {
            return redirect()->route('nasabah.pledges.create')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified pledge
     */
    public function show($id)
    {
        $user = auth()->user();
        $pledge = $user->goldPledges()
            ->with(['payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }, 'extensions'])
            ->findOrFail($id);

        return view('nasabah.pledges.show', compact('pledge'));
    }

    /**
     * Show the form for making a payment
     */
    public function payment($id)
    {
        $user = auth()->user();
        $pledge = $user->goldPledges()->findOrFail($id);

        if ($pledge->isPaid()) {
            return redirect()->route('nasabah.pledges.show', $pledge->id)
                ->with('error', 'Gadai emas sudah lunas.');
        }

        $remainingAmount = $pledge->getRemainingAmount();
        $monthlyInstallment = $pledge->getMonthlyInstallment();

        return view('nasabah.pledges.payment', compact('pledge', 'remainingAmount', 'monthlyInstallment'));
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
            'payment_type' => ['required', 'in:installment,full_payment'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $pledge = $user->goldPledges()->findOrFail($id);

        try {
            $payment = $this->pledgeService->processPayment($pledge, [
                'payment_amount' => $request->payment_amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'payment_type' => $request->payment_type,
                'notes' => $request->notes,
                'auto_verify' => false,
            ]);

            return redirect()->route('nasabah.pledges.show', $pledge->id)
                ->with('success', 'Pembayaran berhasil dikirim. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            return redirect()->route('nasabah.pledges.payment', $pledge->id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show redeem form
     */
    public function redeem($id)
    {
        $user = auth()->user();
        $pledge = $user->goldPledges()->findOrFail($id);

        if (!$pledge->isPaid()) {
            return redirect()->route('nasabah.pledges.show', $pledge->id)
                ->with('error', 'Gadai emas belum lunas. Sisa yang harus dibayar: Rp ' . number_format($pledge->getRemainingAmount(), 0, ',', '.'));
        }

        return view('nasabah.pledges.redeem', compact('pledge'));
    }

    /**
     * Process redeem
     */
    public function processRedeem(Request $request, $id)
    {
        $user = auth()->user();
        $pledge = $user->goldPledges()->findOrFail($id);

        try {
            $this->pledgeService->redeemGold($pledge);

            return redirect()->route('nasabah.pledges.index')
                ->with('success', 'Emas berhasil ditebus dan dikembalikan ke tabungan Anda.');

        } catch (\Exception $e) {
            return redirect()->route('nasabah.pledges.show', $pledge->id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

