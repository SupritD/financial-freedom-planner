<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Domain\Income\Models\IncomeEntry;
use Domain\Expense\Models\Expense;
use Domain\SharedKernel\Models\LedgerAccount;
use Domain\Goal\Models\FinancialGoal;
use Domain\Debt\Models\DebtAccount;
use App\Models\EmergencyFund;
use PragmaRX\Google2FAQRCode\Google2FA;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $qrCode = null;
        $secret = null;

        if ($request->session()->has('2fa_secret_setup')) {
            $secret = $request->session()->get('2fa_secret_setup');
            $google2fa = new Google2FA();
            $qrCode = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret
            );
        }

        return view('profile', compact('user', 'qrCode', 'secret'));
    }

    public function enableTwoFactor(Request $request)
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $request->session()->put('2fa_secret_setup', $secret);

        return back()->with('success', 'Scan the QR code to set up Two-Factor Authentication.');
    }

    public function confirmTwoFactor(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $secret = $request->session()->get('2fa_secret_setup');
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            $user = Auth::user();
            $user->two_factor_secret = $secret;
            $user->save();
            $request->session()->forget('2fa_secret_setup');
            return back()->with('success', 'Two-Factor Authentication has been successfully enabled!');
        }

        return back()->withErrors(['code' => 'Invalid authentication code. Please try again.']);
    }

    public function disableTwoFactor(Request $request)
    {
        $request->validate(['password' => 'required|string']);
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['disable_2fa_password' => 'Incorrect password.']);
        }

        $user->two_factor_secret = null;
        $user->save();

        return back()->with('success', 'Two-Factor Authentication has been disabled.');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function exportData()
    {
        $user = Auth::user();
        
        $data = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'joined_at' => $user->created_at->toIso8601String(),
            ],
            'incomes' => IncomeEntry::where('user_id', $user->id)->with('sourceType')->get()->map(function($i) {
                return [
                    'source' => $i->sourceType->name,
                    'amount' => $i->amount,
                    'date' => $i->income_date->format('Y-m-d')
                ];
            }),
            'expenses' => Expense::where('user_id', $user->id)->with('category')->get()->map(function($e) {
                return [
                    'category' => $e->category->name ?? 'Uncategorized',
                    'title' => $e->title,
                    'amount' => $e->amount,
                    'date' => $e->expense_date->format('Y-m-d')
                ];
            }),
            'accounts' => LedgerAccount::where('user_id', $user->id)->get(['account_type', 'name', 'current_balance']),
            'goals' => FinancialGoal::where('user_id', $user->id)->get(['name', 'target_amount', 'current_amount', 'deadline', 'is_completed']),
            'debts' => DebtAccount::where('user_id', $user->id)->get(['name', 'type', 'principal_amount', 'current_balance', 'interest_rate', 'is_paid_off']),
            'emergency_fund' => EmergencyFund::where('user_id', $user->id)->first(['monthly_expenses', 'recommended_months', 'current_amount'])
        ];

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="financial_data_export.json"'
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Account deletion failed.']);
        }

        // The user deletion will cascade to most tables based on foreign keys defined in migrations.
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been securely deleted.');
    }
}
