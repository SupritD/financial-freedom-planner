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
}
