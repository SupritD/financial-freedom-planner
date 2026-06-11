<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domain\SharedKernel\Models\LedgerAccount;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $investments = LedgerAccount::where('user_id', $user->id)
            ->where('account_type', 'investment')
            ->get();
            
        $totalInvestments = $investments->sum('current_balance');

        return view('investments', compact('investments', 'totalInvestments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'initial_value' => 'nullable|numeric|min:0',
        ]);

        LedgerAccount::create([
            'user_id' => Auth::id(),
            'tenant_id' => Auth::user()->tenant_id,
            'account_type' => 'investment',
            'name' => $data['name'],
            'current_balance' => $data['initial_value'] ?? 0,
            'is_system' => false,
        ]);

        return back()->with('success', 'Investment tracked successfully!');
    }

    public function updateValue(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:ledger_accounts,id',
            'new_value' => 'required|numeric|min:0',
        ]);

        $investment = LedgerAccount::where('id', $request->account_id)
            ->where('user_id', Auth::id())
            ->where('account_type', 'investment')
            ->firstOrFail();

        $investment->current_balance = $request->new_value;
        $investment->save();

        return back()->with('success', 'Investment value updated successfully!');
    }
}
