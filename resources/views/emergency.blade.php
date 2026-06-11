@extends('layouts.app')

@section('title', 'Emergency Fund | Financial Freedom Planner')
@section('header', 'Emergency Fund')

@section('content')

    @if(session('success'))
        <div class="bg-brand-success/10 text-brand-success p-4 rounded-xl flex justify-between items-center mb-6 border border-brand-success/20">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-xl hover:text-brand-success/80">&times;</button>
        </div>
    @endif

    @if(!$fund)
        <!-- Setup Wizard -->
        <div class="glass-card max-w-2xl mx-auto text-center py-12 px-8">
            <i class="ph ph-shield-check text-6xl text-brand-accent-primary mb-6 inline-block"></i>
            <h2 class="text-2xl font-bold mb-4 text-brand-text-primary">Build Your Safety Net</h2>
            <p class="text-brand-text-secondary mb-8 leading-relaxed">
                An emergency fund protects you from unexpected expenses and job loss. Enter your average monthly expenses to calculate your required target.
            </p>

            <form method="POST" action="{{ route('emergency.store') }}" class="text-left space-y-6">
                @csrf
                <div>
                    <label class="form-label">Average Monthly Expenses (₹)</label>
                    <input type="number" step="1" name="monthly_expenses" class="form-input" required placeholder="e.g. 30000">
                </div>
                <div>
                    <label class="form-label">Target Coverage (Months)</label>
                    <select name="recommended_months" class="form-input" required>
                        <option value="3">3 Months (Minimum)</option>
                        <option value="6" selected>6 Months (Recommended)</option>
                        <option value="12">12 Months (Conservative)</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full">Calculate Target</button>
                </div>
            </form>
        </div>
    @else
        <!-- Active Dashboard -->
        @php
            $required = $fund->monthly_expenses * $fund->recommended_months;
            $progress = $required > 0 ? min(100, ($fund->current_amount / $required) * 100) : 0;
            $gap = max(0, $required - $fund->current_amount);
            $coverageMonths = $fund->monthly_expenses > 0 ? floor($fund->current_amount / $fund->monthly_expenses) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="glass-card">
                <div class="text-brand-text-secondary font-medium text-sm mb-2">Required Target ({{ $fund->recommended_months }} Months)</div>
                <div class="text-3xl font-bold text-brand-text-primary">₹{{ number_format($required, 2) }}</div>
            </div>
            <div class="glass-card">
                <div class="text-brand-text-secondary font-medium text-sm mb-2">Current Balance</div>
                <div class="text-3xl font-bold text-brand-accent-primary">₹{{ number_format($fund->current_amount, 2) }}</div>
            </div>
            <div class="glass-card">
                <div class="text-brand-text-secondary font-medium text-sm mb-2">Remaining Gap</div>
                <div class="text-3xl font-bold text-brand-danger">₹{{ number_format($gap, 2) }}</div>
            </div>
        </div>

        <div class="glass-card mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-brand-text-primary">Funding Progress</h3>
                <span class="text-brand-text-secondary font-medium">{{ number_format($progress, 1) }}% Funded</span>
            </div>
            
            <div class="w-full h-6 bg-black/30 rounded-full overflow-hidden mb-6">
                <div class="h-full bg-brand-accent-primary transition-all duration-1000 ease-in-out" style="width: {{ $progress }}%;"></div>
            </div>

            <p class="text-brand-text-secondary flex items-center gap-2">
                <i class="ph ph-info text-lg"></i> Your emergency fund currently covers <strong class="text-brand-text-primary">{{ $coverageMonths }} full months</strong> of living expenses.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Deposit Card -->
            <div class="glass-card text-center cursor-pointer transition-all duration-300 border-2 border-dashed border-brand-border hover:border-brand-success group" 
                 onclick="document.getElementById('depositModal').classList.remove('hidden')">
                <i class="ph ph-piggy-bank text-5xl text-brand-success mb-4 inline-block group-hover:scale-110 transition-transform"></i>
                <h3 class="text-xl font-semibold text-brand-text-primary mb-2">Add Funds</h3>
                <p class="text-brand-text-secondary text-sm">Deposit money to your emergency fund</p>
            </div>
            
            <!-- Update Plan Card -->
            <div class="glass-card text-center opacity-60 hover:opacity-100 transition-opacity cursor-not-allowed">
                <i class="ph ph-sliders text-5xl text-brand-text-secondary mb-4 inline-block"></i>
                <h3 class="text-xl font-semibold text-brand-text-primary mb-2">Update Expenses</h3>
                <p class="text-brand-text-secondary text-sm">Adjust your monthly expense baseline (Coming soon)</p>
            </div>
        </div>

        <!-- Deposit Modal -->
        <div id="depositModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 hidden">
            <div class="glass-card w-full max-w-md relative">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-brand-text-primary">Deposit to Emergency Fund</h3>
                    <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl" onclick="document.getElementById('depositModal').classList.add('hidden')">&times;</button>
                </div>
                <form method="POST" action="{{ route('emergency.deposit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Deposit Amount (₹)</label>
                        <input type="number" step="1" name="amount" class="form-input" required placeholder="10000">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="btn-primary !bg-brand-success hover:!bg-brand-success/90 border-none w-full">Record Deposit</button>
                    </div>
                </form>
            </div>
        </div>

    @endif

@endsection
