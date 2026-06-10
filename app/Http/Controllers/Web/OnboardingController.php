<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    public function show()
    {
        if (Auth::user()->is_onboarded) {
            return redirect()->route('dashboard');
        }
        return view('onboarding');
    }

    public function store(Request $request)
    {
        // This is a simplified onboarding save to demonstrate progress.
        // It should normally invoke Domain Actions to save these records properly.
        
        $request->validate([
            'monthly_salary' => 'nullable|numeric',
            'monthly_expenses' => 'nullable|numeric',
            'savings' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $request) {
            // Save the data to appropriate tables using raw queries or models if they exist.
            // For now, we will mark the user as onboarded.
            $user->update([
                'is_onboarded' => true
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Onboarding complete!');
    }
}
