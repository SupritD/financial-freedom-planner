<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domain\SharedKernel\Models\LedgerAccount;
use Illuminate\Support\Facades\Auth;

class SavingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $savingsBuckets = LedgerAccount::where('user_id', $user->id)
            ->where('account_type', 'savings')
            ->get();
            
        $totalSavings = $savingsBuckets->sum('current_balance');

        return view('savings', compact('savingsBuckets', 'totalSavings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'initial_amount' => 'nullable|numeric|min:0',
        ]);

        LedgerAccount::create([
            'user_id' => Auth::id(),
            'tenant_id' => Auth::user()->tenant_id,
            'account_type' => 'savings',
            'name' => $data['name'],
            'current_balance' => $data['initial_amount'] ?? 0,
            'is_system' => false,
        ]);

        return back()->with('success', 'Savings bucket created successfully!');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:ledger_accounts,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $bucket = LedgerAccount::where('id', $request->account_id)
            ->where('user_id', Auth::id())
            ->where('account_type', 'savings')
            ->firstOrFail();

        $bucket->current_balance += $request->amount;
        $bucket->save();

        return back()->with('success', 'Deposit added successfully!');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:ledger_accounts,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $bucket = LedgerAccount::where('id', $request->account_id)
            ->where('user_id', Auth::id())
            ->where('account_type', 'savings')
            ->firstOrFail();

        if ($request->amount > $bucket->current_balance) {
            return back()->withErrors(['amount' => 'Insufficient funds in this bucket.']);
        }

        $bucket->current_balance -= $request->amount;
        $bucket->save();

        return back()->with('success', 'Withdrawal successful!');
    }
}
