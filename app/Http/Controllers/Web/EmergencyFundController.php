<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmergencyFund;

class EmergencyFundController extends Controller
{
    public function index()
    {
        $fund = EmergencyFund::where('user_id', Auth::id())->first();
        return view('emergency', compact('fund'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'monthly_expenses' => 'required|numeric|min:1',
            'recommended_months' => 'nullable|integer|min:3|max:12',
        ]);

        EmergencyFund::create([
            'user_id' => Auth::id(),
            'tenant_id' => Auth::user()->tenant_id,
            'monthly_expenses' => $data['monthly_expenses'],
            'recommended_months' => $data['recommended_months'] ?? 6,
            'current_amount' => 0,
        ]);

        return back()->with('success', 'Emergency Fund target set!');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $fund = EmergencyFund::where('user_id', Auth::id())->firstOrFail();
        $fund->current_amount += $request->amount;
        $fund->save();

        return back()->with('success', 'Deposit added successfully!');
    }
}
