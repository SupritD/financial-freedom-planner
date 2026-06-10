<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
            'timezone' => 'nullable|timezone|max:50',
            'currency' => 'nullable|string|size:3',
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|max:128|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Invalidate other sessions (simplified for this context)
        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Password changed successfully.');
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        if ($user->two_factor_secret) {
            // Disable
            $user->update([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
            ]);
            return back()->with('success', 'Two-Factor Authentication disabled.');
        } else {
            // Enable (simulated generation of secret)
            $user->update([
                'two_factor_secret' => encrypt('dummy-totp-secret-' . str()->random(16)),
                'two_factor_recovery_codes' => encrypt(json_encode([str()->random(10), str()->random(10)])),
            ]);
            return back()->with('success', 'Two-Factor Authentication enabled. (Simulated)');
        }
    }

    public function downloadData(Request $request)
    {
        $user = Auth::user();
        
        // Fetch all relevant data for export
        $exportData = [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'currency' => $user->currency,
                'timezone' => $user->timezone,
                'created_at' => $user->created_at,
            ],
            'ledger_entries' => \App\Models\LedgerEntry::whereHas('tenant', function($q) use ($user) {
                $q->where('id', $user->tenant_id);
            })->get(),
            'financial_goals' => \App\Models\FinancialGoal::where('user_id', $user->id)->get(),
            'debts' => \App\Models\DebtAccount::where('user_id', $user->id)->get(),
        ];

        return response()->json($exportData, 200, [
            'Content-Disposition' => 'attachment; filename="financial_data_export.json"'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => 'required|current_password',
            'delete_confirmation' => 'required|string|in:DELETE',
        ], [
            'delete_confirmation.in' => 'Please type DELETE in capitals to confirm',
            'password.current_password' => 'Current password is incorrect',
        ]);

        // Soft delete the user
        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
