<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Domain\Debt\Models\DebtAccount;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $debts = DebtAccount::where('user_id', $user->id)
            ->get()
            ->map(function ($debt) {
                $paid = $debt->principal_amount - $debt->current_balance;
                $percentage = $debt->principal_amount > 0 
                    ? min(100, round(($paid / $debt->principal_amount) * 100))
                    : 0;

                return [
                    'name' => $debt->name,
                    'type' => ucfirst(str_replace('_', ' ', $debt->type)),
                    'principal_amount' => $debt->principal_amount,
                    'current_balance' => $debt->current_balance,
                    'interest_rate' => $debt->interest_rate,
                    'percentage' => $percentage,
                    'is_paid_off' => $debt->is_paid_off,
                ];
            });

        return view('debt', compact('debts'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:credit_card,personal_loan,mortgage,auto_loan,student_loan',
            'principal_amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
        ]);

        DebtAccount::create([
            'user_id' => Auth::id(),
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $data['name'],
            'type' => $data['type'],
            'principal_amount' => $data['principal_amount'],
            'current_balance' => $data['principal_amount'],
            'interest_rate' => $data['interest_rate'],
            'currency' => 'INR',
            'is_paid_off' => false,
        ]);

        return back()->with('success', 'Debt account created successfully!');
    }
}
