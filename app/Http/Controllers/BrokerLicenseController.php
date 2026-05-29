<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\BrokerLicenseApprovalRequested;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class BrokerLicenseController extends Controller
{
    public function show(Request $request): View
    {
        abort_unless($request->user(), 403);

        return view('broker.license', [
            'user' => $request->user(),
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user || (! $user->hasRole(User::ROLE_BROKER) && ! $user->hasRole(User::ROLE_BUYER))) {
            return redirect()->route('dashboard')->with('error', 'Only buyers or brokers can submit dealer license details.');
        }

        $validated = $request->validate([
            'dealer_license_number' => ['required', 'string', 'max:100'],
            'dealer_license_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $documentPath = $user->dealer_license_document_path;
        if ($request->hasFile('dealer_license_document')) {
            $documentPath = $request->file('dealer_license_document')->store('dealer-licenses', 'public');
        }

        $user->forceFill([
            'dealer_license_number' => $validated['dealer_license_number'],
            'dealer_license_document_path' => $documentPath,
            'dealer_license_status' => 'pending',
            'dealer_license_admin_notes' => null,
            'dealer_license_submitted_at' => now(),
            'dealer_license_reviewed_at' => null,
            'dealer_license_reviewed_by' => null,
        ])->save();

        $admins = User::where('role', User::ROLE_ADMIN)->get();

        try {
            Notification::send($admins, new BrokerLicenseApprovalRequested($user));
        } catch (\Throwable $e) {
            Log::error('Failed sending broker license approval request notification', [
                'broker_id' => $user->id,
                'exception' => $e,
            ]);
        }

        return redirect()
            ->route('broker.license.show')
            ->with('success', 'Dealer license details submitted. Admin has been notified for approval.');
    }
}
