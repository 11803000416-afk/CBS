<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\BrokerLicenseApprovalStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BrokerLicenseApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $requests = User::query()
            ->whereIn('role', [User::ROLE_BROKER, User::ROLE_BUYER])
            ->where(function ($query) {
                $query->whereNotNull('dealer_license_number')
                    ->orWhereIn('dealer_license_status', ['pending', 'approved', 'rejected']);
            })
            ->when($status, function ($query, $statusValue) {
                $query->where('dealer_license_status', $statusValue);
            })
            ->latest('dealer_license_submitted_at')
            ->paginate(12)
            ->withQueryString();

        $pendingCount = User::whereIn('role', [User::ROLE_BROKER, User::ROLE_BUYER])
            ->where('dealer_license_status', 'pending')
            ->count();

        return view('admin.broker-licenses.index', [
            'requests' => $requests,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function show(User $user): View
    {
        abort_unless($user->hasRole([User::ROLE_BROKER, User::ROLE_BUYER]), 404);

        $reviewer = null;
        if ($user->dealer_license_reviewed_by) {
            $reviewer = User::find($user->dealer_license_reviewed_by);
        }

        return view('admin.broker-licenses.show', [
            'broker' => $user,
            'reviewer' => $reviewer,
        ]);
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->hasRole([User::ROLE_BROKER, User::ROLE_BUYER]), 404);

        $validated = $request->validate([
            'dealer_license_admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->forceFill([
            'dealer_license_status' => 'approved',
            'dealer_license_admin_notes' => $validated['dealer_license_admin_notes'] ?? null,
            'dealer_license_reviewed_at' => now(),
            'dealer_license_reviewed_by' => $request->user()->id,
        ])->save();

        if ($user->hasRole(User::ROLE_BUYER)) {
            $user->update(['role' => User::ROLE_BROKER]);
        }

        try {
            $user->notify(new BrokerLicenseApprovalStatusUpdated('approved', $validated['dealer_license_admin_notes'] ?? null));
        } catch (\Throwable $e) {
            Log::error('Failed sending broker approval status notification', [
                'broker_id' => $user->id,
                'exception' => $e,
            ]);
        }

        return redirect()->route('admin.broker-licenses.index')
            ->with('success', "Broker dealer license approved for {$user->name}.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->hasRole([User::ROLE_BROKER, User::ROLE_BUYER]), 404);

        $validated = $request->validate([
            'dealer_license_admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $user->forceFill([
            'dealer_license_status' => 'rejected',
            'dealer_license_admin_notes' => $validated['dealer_license_admin_notes'],
            'dealer_license_reviewed_at' => now(),
            'dealer_license_reviewed_by' => $request->user()->id,
        ])->save();

        try {
            $user->notify(new BrokerLicenseApprovalStatusUpdated('rejected', $validated['dealer_license_admin_notes']));
        } catch (\Throwable $e) {
            Log::error('Failed sending broker rejection status notification', [
                'broker_id' => $user->id,
                'exception' => $e,
            ]);
        }

        return redirect()->route('admin.broker-licenses.index')
            ->with('success', "Broker dealer license rejected for {$user->name}.");
    }
}
