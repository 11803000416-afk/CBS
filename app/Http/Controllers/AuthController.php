<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\User;
use App\Notifications\BrokerLicenseApprovalRequested;
use App\Notifications\WelcomeNewUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        // Regenerate session ID to prevent session fixation
        $request->session()->regenerate(true);

        // Check if email is verified. Keep the user signed-in so they can access
        // the verification notice (route is protected by the `auth` middleware).
        if (!Auth::user()->hasVerifiedEmail()) {
            // Allow admins to login even if their email isn't verified
            if (Auth::user()->role !== User::ROLE_ADMIN) {
                try {
                    Auth::user()->sendEmailVerificationNotification();
                } catch (\Throwable $e) {
                    Log::error('Failed to send verification email during login', [
                        'user_id' => Auth::user()->id,
                        'exception' => $e,
                    ]);
                }

                return redirect()->route('verification.notice');
            }
        }

        $user = Auth::user();

        // If a broker already has dealer license details, auto-submit for admin approval
        // on first login and notify admin by email.
        if ($user->shouldNotifyAdminForBrokerApproval()) {
            $user->forceFill([
                'dealer_license_status' => 'pending',
                'dealer_license_submitted_at' => now(),
            ])->save();

            try {
                $adminUsers = User::where('role', User::ROLE_ADMIN)->get();
                Notification::send($adminUsers, new BrokerLicenseApprovalRequested($user));
            } catch (\Throwable $e) {
                Log::error('Failed sending broker dealer license approval notification', [
                    'broker_id' => $user->id,
                    'exception' => $e,
                ]);
            }
        }

        return redirect()->route('dashboard');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => User::ROLE_BUYER,
            'password' => Hash::make($data['password']),
        ]);

        Buyer::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone ?? 'N/A',
            'email' => $user->email,
            'address' => $user->address,
            'status' => 'active',
        ]);

        // Send verification email to user (fail gracefully if mail transport is unavailable)
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Failed to send verification email during registration', ['exception' => $e]);
        }

        // Send a welcome email/notification to the user on first-time registration
        try {
            $user->notify(new WelcomeNewUser($user));
        } catch (\Throwable $e) {
            Log::error('Failed to send welcome notification during registration', ['user_id' => $user->id ?? null, 'exception' => $e]);
        }

        return redirect()->route('login')->with('success', 'Account created successfully! You now have access to the Car Broker System. You can buy and sell cars from CBS with our user-friendly platform.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showEmailVerification(): View
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->user()->markEmailAsVerified();

        return redirect()->route('dashboard')->with('status', 'Email verified successfully!');
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Failed to resend verification email', ['user_id' => $request->user()?->id, 'exception' => $e]);
            return back()->with('error', 'Unable to send verification email right now. Please try again later.');
        }

        return back()->with('status', 'Verification link sent!');
    }
}
