<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Domain\Goal\Models\FinancialGoal;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $goals = FinancialGoal::where('user_id', $user->id)
            ->orderBy('deadline', 'asc')
            ->get()
            ->map(function ($goal) {
                $percentage = $goal->target_amount > 0 
                    ? min(100, round(($goal->current_amount / $goal->target_amount) * 100))
                    : 0;

                return [
                    'id' => $goal->id,
                    'name' => $goal->name,
                    'target_amount' => $goal->target_amount,
                    'current_amount' => $goal->current_amount,
                    'deadline' => $goal->deadline ? $goal->deadline->format('Y-m-d') : 'No deadline',
                    'percentage' => $percentage,
                    'is_completed' => $goal->is_completed,
                ];
            });

        return view('goals', compact('goals'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
        ]);

        FinancialGoal::create([
            'user_id' => Auth::id(),
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $data['name'],
            'target_amount' => $data['target_amount'],
            'current_amount' => 0,
            'currency' => 'INR',
            'deadline' => $data['deadline'] ?? null,
            'is_completed' => false,
        ]);

        return back()->with('success', 'Goal created successfully!');
    }

    public function contribute(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'goal_id' => 'required|exists:financial_goals,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $goal = FinancialGoal::where('id', $request->goal_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $goal->current_amount += $request->amount;
        
        if ($goal->current_amount >= $goal->target_amount) {
            $goal->is_completed = true;
        }
        
        $goal->save();

        return back()->with('success', 'Contribution added successfully!');
    }
}
