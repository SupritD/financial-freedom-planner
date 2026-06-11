<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Domain\SharedKernel\Models\Tenant;
use Domain\SharedKernel\Models\LedgerAccount;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTenants = Tenant::count();

        // Platform-wide Savings (All users)
        $globalSavings = LedgerAccount::where('account_type', 'savings')->sum('current_balance');
        
        // Platform-wide Debt (All users)
        $globalDebt = LedgerAccount::where('account_type', 'debt')->sum('current_balance');

        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTenants',
            'globalSavings',
            'globalDebt',
            'recentUsers'
        ));
    }
}
