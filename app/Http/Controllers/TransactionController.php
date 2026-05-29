<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\User;
use App\Notifications\TransactionPaymentRequested;
use App\Notifications\TransactionPaymentApproved;
use App\Notifications\TransactionPaymentRejected;
use App\Notifications\TransactionOtpSent;
use App\Notifications\TransactionOtpVerified;
use App\Models\TransactionOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = Transaction::with(['vehicle', 'buyer', 'seller', 'broker']);
        
        // Filter transactions based on user role
        if ($user->hasRole(User::ROLE_SELLER)) {
            // Sellers see only their own transactions
            $seller = Seller::where('user_id', $user->id)->first();
            if ($seller) {
                $query->where('seller_id', $seller->id);
            }
        } elseif ($user->hasRole(User::ROLE_BUYER)) {
            // Buyers see only their own transactions
            $buyer = Buyer::where('user_id', $user->id)->first();
            if ($buyer) {
                $query->where('buyer_id', $buyer->id);
            }
        }
        // Admins see all transactions (no filter)
        
        $transactions = $query->latest('id')->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::where('status', '!=', 'sold')->orderBy('brand')->limit(100)->get();
        $buyers = Buyer::where('status', 'active')->orderBy('name')->limit(100)->get();
        $sellers = Seller::where('status', 'active')->orderBy('name')->limit(100)->get();

        return view('transactions.form', ['transaction' => new Transaction(), 'vehicles' => $vehicles, 'buyers' => $buyers, 'sellers' => $sellers]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id', 'unique:transactions,vehicle_id'],
            'buyer_id' => ['required', 'exists:buyers,id'],
            'seller_id' => ['required', 'exists:sellers,id'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'broker_commission' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending_review,completed,cancelled'],
            'completed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'agreement_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,txt', 'max:10240'],
        ]);

        // Handle file upload
        $agreementPath = null;
        if ($request->hasFile('agreement_file')) {
            $agreementPath = $request->file('agreement_file')->store('agreements/transactions', 'public');
        }

        $transaction = Transaction::create([
            ...$data,
            'broker_id' => $request->user()->id,
            'agreement_file' => $agreementPath,
            'agreement_uploaded_at' => $agreementPath ? now() : null,
            'payment_requested_at' => $data['status'] === 'pending_review' ? now() : null,
        ]);

        if ($transaction->status === 'completed') {
            $transaction->vehicle->update(['status' => 'sold']);
        } else {
            $transaction->vehicle->update(['status' => 'available']);
        }

        if ($transaction->status === 'pending_review') {
            // generate OTP and notify buyer/seller, then notify admins
            $this->generateAndSendOtp($transaction);
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                try {
                    Notification::send($admins, new TransactionPaymentRequested($transaction));
                } catch (\Throwable $e) {
                    Log::error('Failed to notify admins about payment request', [
                        'transaction_id' => $transaction->id,
                        'exception' => $e,
                    ]);
                }
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction saved successfully.');
    }

    public function edit(Transaction $transaction): View
    {
        $vehicles = Vehicle::orderBy('brand')->limit(100)->get();
        $buyers = Buyer::where('status', 'active')->orderBy('name')->limit(100)->get();
        $sellers = Seller::where('status', 'active')->orderBy('name')->limit(100)->get();

        return view('transactions.form', compact('transaction', 'vehicles', 'buyers', 'sellers'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id', 'unique:transactions,vehicle_id,' . $transaction->id],
            'buyer_id' => ['required', 'exists:buyers,id'],
            'seller_id' => ['required', 'exists:sellers,id'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'broker_commission' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending_review,completed,cancelled'],
            'completed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'agreement_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,txt', 'max:10240'],
        ]);

        // Handle file upload (replace old file if new one uploaded)
        if ($request->hasFile('agreement_file')) {
            // Delete old file if exists
            if ($transaction->agreement_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->agreement_file);
            }
            $data['agreement_file'] = $request->file('agreement_file')->store('agreements/transactions', 'public');
            $data['agreement_uploaded_at'] = now();
        }

        if ($data['status'] === 'pending_review' && !$transaction->payment_requested_at) {
            $data['payment_requested_at'] = now();
        }

        $transaction->update($data);

        if ($transaction->status === 'completed') {
            $transaction->vehicle->update(['status' => 'sold']);
        } else {
            $transaction->vehicle->update(['status' => 'available']);
        }

        if ($transaction->status === 'pending_review') {
            $this->generateAndSendOtp($transaction);
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                try {
                    Notification::send($admins, new TransactionPaymentRequested($transaction));
                } catch (\Throwable $e) {
                    Log::error('Failed to notify admins about payment request update', [
                        'transaction_id' => $transaction->id,
                        'exception' => $e,
                    ]);
                }
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function requestPayment(Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $transaction->update([
            'status' => 'pending_review',
            'payment_requested_at' => now(),
        ]);

        // generate OTP and notify buyer/seller, then notify admins
        $this->generateAndSendOtp($transaction);
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            try {
                Notification::send($admins, new TransactionPaymentRequested($transaction));
            } catch (\Throwable $e) {
                Log::error('Failed to notify admins about manual payment request', [
                    'transaction_id' => $transaction->id,
                    'exception' => $e,
                ]);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Payment request sent to CBS admin for approval.');
    }

    public function verifyOtp(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $data = $request->validate([
            'otp_code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp_code.required' => 'OTP code is required.',
            'otp_code.size' => 'OTP code must be exactly 6 digits.',
            'otp_code.regex' => 'OTP code must contain only digits.',
        ]);

        $otp = TransactionOtp::where('transaction_id', $transaction->id)
            ->where('code', $data['otp_code'])
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Invalid or expired OTP code. Please check the code and try again.']);
        }

        $otp->update(['used_at' => now()]);
        $transaction->update(['otp_verified_at' => now()]);

        $recipients = collect([
            $transaction->broker,
            $transaction->buyer?->user,
            $transaction->seller?->user,
        ])->filter();

        $admins = User::where('role', User::ROLE_ADMIN)->get();
        if ($admins->isNotEmpty()) {
            $recipients = $recipients->merge($admins);
        }

        $uniqueRecipients = $recipients->unique('id')->values()->all();
        if (!empty($uniqueRecipients)) {
            try {
                Notification::send($uniqueRecipients, new TransactionOtpVerified($transaction));
            } catch (\Throwable $e) {
                Log::error('Failed to notify users about OTP verification', [
                    'transaction_id' => $transaction->id,
                    'exception' => $e,
                ]);
            }
        }

        Log::info('OTP verified successfully', [
            'transaction_id' => $transaction->id,
            'verified_at' => now(),
        ]);

        return redirect()->route('transactions.edit', $transaction)->with('success', 'OTP verified successfully! Awaiting CBS admin approval.');
    }

    /**
     * Generate a 6-digit OTP, store and send notification to buyer/seller.
     */
    private function generateAndSendOtp(Transaction $transaction): void
    {
        try {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expires = now()->addMinutes(15);

            $otp = TransactionOtp::create([
                'transaction_id' => $transaction->id,
                'code' => $code,
                'sent_to' => 'both',
                'expires_at' => $expires,
            ]);

            $recipients = [];
            if ($transaction->buyer && $transaction->buyer->user) {
                $recipients[] = $transaction->buyer->user;
            } elseif ($transaction->buyer && $transaction->buyer->email) {
                // fall back to routing by email
                Notification::route('mail', $transaction->buyer->email)->notify(new TransactionOtpSent($otp));
            }

            if ($transaction->seller && $transaction->seller->user) {
                $recipients[] = $transaction->seller->user;
            } elseif ($transaction->seller && $transaction->seller->email) {
                Notification::route('mail', $transaction->seller->email)->notify(new TransactionOtpSent($otp));
            }

            if (!empty($recipients)) {
                Notification::send($recipients, new TransactionOtpSent($otp));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to generate/send transaction OTP', ['transaction_id' => $transaction->id, 'exception' => $e]);
        }
    }

    public function approvePayment(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $data = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'review_notes' => $data['review_notes'] ?? null,
        ]);

        $transaction->vehicle->update(['status' => 'sold']);

        $recipients = array_filter([
            $transaction->buyer?->user,
            $transaction->seller?->user,
        ]);

        if (!empty($recipients)) {
            try {
                Notification::send($recipients, new TransactionPaymentApproved($transaction));
            } catch (\Throwable $e) {
                Log::error('Failed to notify users about payment approval', [
                    'transaction_id' => $transaction->id,
                    'exception' => $e,
                ]);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Payment approved and transaction completed.');
    }

    public function rejectPayment(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $data = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $transaction->update([
            'status' => 'cancelled',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'review_notes' => $data['review_notes'] ?? null,
        ]);

        $transaction->vehicle->update(['status' => 'available']);

        $recipients = array_filter([
            $transaction->buyer?->user,
            $transaction->seller?->user,
        ]);

        if (!empty($recipients)) {
            try {
                Notification::send($recipients, new TransactionPaymentRejected($transaction));
            } catch (\Throwable $e) {
                Log::error('Failed to notify users about payment rejection', [
                    'transaction_id' => $transaction->id,
                    'exception' => $e,
                ]);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Payment request rejected.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $vehicle = $transaction->vehicle;

        $transaction->delete();

        if ($vehicle) {
            $vehicle->update(['status' => 'available']);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function downloadAgreement(Transaction $transaction)
    {
        if (!$transaction->agreement_file) {
            return redirect()->route('transactions.index')->with('error', 'No agreement file found for this transaction.');
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($transaction->agreement_file)) {
            return redirect()->route('transactions.index')->with('error', 'Agreement file not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($transaction->agreement_file);
    }
}
