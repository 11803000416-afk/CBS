<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show 2FA setup page.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $twoFactor = $user->getOrCreateTwoFactorAuthentication();

        if (!$twoFactor->two_factor_secret) {
            $twoFactor->two_factor_secret = $this->google2fa->generateSecretKey();
            $twoFactor->save();
        }

        $qrCode = $this->google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $twoFactor->two_factor_secret
        );

        return view('auth.two-factor-authentication', [
            'qrCode' => $qrCode,
            'secret' => $twoFactor->two_factor_secret,
            'recoveryCodes' => $this->generateRecoveryCodes(),
        ]);
    }

    /**
     * Enable 2FA for the user.
     */
    public function enable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ], [
            'code.required' => 'Verification code is required.',
            'code.numeric' => 'Verification code must be numeric.',
            'code.digits' => 'Verification code must be 6 digits.',
        ]);

        $user = $request->user();
        $twoFactor = $user->getOrCreateTwoFactorAuthentication();

        if (!$this->google2fa->verifyKey($twoFactor->two_factor_secret, $request->code)) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();
        $twoFactor->two_factor_recovery_codes = json_encode($recoveryCodes);
        $twoFactor->two_factor_confirmed = true;
        $twoFactor->two_factor_enabled_at = now();
        $twoFactor->save();

        return redirect('/dashboard')->with('success', '2FA enabled successfully. Save your recovery codes in a safe place.');
    }

    /**
     * Verify 2FA code during login.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = auth()->user();
        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect('/login')->withErrors(['code' => 'Invalid request.']);
        }

        $twoFactor = $user->twoFactorAuthentication;
        $code = str_replace(' ', '', $request->code);

        // Check if it's a recovery code
        if ($this->isRecoveryCode($code)) {
            if ($twoFactor->useRecoveryCode($code)) {
                session(['two_factor_verified' => true]);
                return redirect('/dashboard')->with('success', 'Logged in successfully. Please generate new recovery codes.');
            }
            return back()->withErrors(['code' => 'Invalid recovery code.']);
        }

        // Check if it's a 2FA code
        if (!$this->google2fa->verifyKey($twoFactor->two_factor_secret, $code)) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        session(['two_factor_verified' => true]);
        return redirect('/dashboard');
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $request->user()->disableTwoFactor();

        return redirect('/dashboard')->with('success', '2FA has been disabled.');
    }

    /**
     * Download recovery codes.
     */
    public function downloadRecoveryCodes(Request $request)
    {
        $user = $request->user();
        $twoFactor = $user->twoFactorAuthentication;

        if (!$twoFactor || !$twoFactor->isEnabled()) {
            return redirect()->back();
        }

        $codes = $twoFactor->getRecoveryCodes();
        $content = "2FA Recovery Codes for " . config('app.name') . "\n";
        $content .= "Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $content .= "User: " . $user->email . "\n";
        $content .= "\n";
        $content .= implode("\n", $codes);

        return response($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="recovery-codes.txt"',
        ]);
    }

    /**
     * Generate recovery codes.
     */
    protected function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = strtoupper(str_random(4) . '-' . str_random(4));
        }
        return $codes;
    }

    /**
     * Check if code is a recovery code format.
     */
    protected function isRecoveryCode(string $code): bool
    {
        return preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}$/', $code);
    }
}
