<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldPledge;
use App\Models\PledgePayment;
use App\Models\PledgeExtension;
use App\Services\PledgeService;
use Illuminate\Http\Request;

class PledgeController extends Controller
{
    protected PledgeService $pledgeService;

    public function __construct(PledgeService $pledgeService)
    {
        $this->pledgeService = $pledgeService;
    }

    /**
     * Display a listing of all pledges
     */
    public function index(Request $request)
    {
        $query = GoldPledge::with(['user', 'payments', 'extensions'])->latest();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pledges = $query->paginate(20);

        $stats = [
            'total' => GoldPledge::count(),
            'pending' => GoldPledge::where('status', 'pending')->count(),
            'active' => GoldPledge::where('status', 'active')->count(),
            'overdue' => GoldPledge::where('status', 'overdue')->count(),
            'paid' => GoldPledge::where('status', 'paid')->count(),
            'pending_verification' => PledgePayment::where('status', 'pending')->count(),
        ];

        return view('admin.pledges.index', compact('pledges', 'stats'));
    }

    /**
     * Display the specified pledge
     */
    public function show($id)
    {
        $pledge = GoldPledge::with([
            'user',
            'payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'payments.verifier',
            'extensions',
            'extensions.approver'
        ])->findOrFail($id);

        return view('admin.pledges.show', compact('pledge'));
    }

    /**
     * Approve pledge request
     */
    public function approve(Request $request, $id)
    {
        $pledge = GoldPledge::findOrFail($id);

        if ($pledge->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Gadai emas ini sudah diproses.');
        }

        try {
            $this->pledgeService->approvePledge($pledge, auth()->id());

            return redirect()->route('admin.pledges.show', $pledge->id)
                ->with('success', 'Pengajuan gadai emas berhasil disetujui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pledge request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        $pledge = GoldPledge::findOrFail($id);

        if ($pledge->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Gadai emas ini sudah diproses.');
        }

        try {
            $pledge->status = 'overdue'; // Or create a 'rejected' status
            $pledge->notes = ($pledge->notes ? $pledge->notes . "\n\n" : '') . "Ditolak: " . $request->notes;
            $pledge->save();

            return redirect()->route('admin.pledges.index')
                ->with('success', 'Pengajuan gadai emas ditolak.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, $paymentId)
    {
        $payment = PledgePayment::with('pledge')->findOrFail($paymentId);

        if ($payment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pembayaran ini sudah diverifikasi atau ditolak.');
        }

        try {
            $this->pledgeService->verifyPayment($payment, auth()->id());

            return redirect()->route('admin.pledges.show', $payment->pledge_id)
                ->with('success', 'Pembayaran berhasil diverifikasi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject payment
     */
    public function rejectPayment(Request $request, $paymentId)
    {
        $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        $payment = PledgePayment::findOrFail($paymentId);

        if ($payment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pembayaran ini sudah diverifikasi atau ditolak.');
        }

        try {
            $payment->reject(auth()->id(), $request->notes);

            return redirect()->route('admin.pledges.show', $payment->pledge_id)
                ->with('success', 'Pembayaran ditolak.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Approve extension
     */
    public function approveExtension(Request $request, $extensionId)
    {
        $extension = PledgeExtension::with('pledge')->findOrFail($extensionId);

        if ($extension->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Perpanjangan ini sudah diproses.');
        }

        try {
            $this->pledgeService->approveExtension($extension, auth()->id());

            return redirect()->route('admin.pledges.show', $extension->pledge_id)
                ->with('success', 'Perpanjangan berhasil disetujui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
