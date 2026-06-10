@extends('layouts.app')

@section('title', 'Emergency Fund | Financial Freedom Planner')
@section('header', 'Emergency Fund')

@section('content')

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(!$fund)
        <!-- Setup Wizard -->
        <div class="glass-card" style="max-width: 600px; margin: 0 auto; text-align: center; padding: 3rem 2rem;">
            <i class="ph ph-shield-check" style="font-size: 4rem; color: var(--accent-primary); margin-bottom: 1.5rem;"></i>
            <h2 style="margin-bottom: 1rem;">Build Your Safety Net</h2>
            <p style="color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6;">
                An emergency fund protects you from unexpected expenses and job loss. Enter your average monthly expenses to calculate your required target.
            </p>

            <form method="POST" action="{{ route('emergency.store') }}" style="text-align: left;">
                @csrf
                <div class="form-group">
                    <label class="form-label">Average Monthly Expenses (₹)</label>
                    <input type="number" step="1" name="monthly_expenses" class="form-input" required placeholder="e.g. 30000">
                </div>
                <div class="form-group">
                    <label class="form-label">Target Coverage (Months)</label>
                    <select name="recommended_months" class="form-input" required>
                        <option value="3">3 Months (Minimum)</option>
                        <option value="6" selected>6 Months (Recommended)</option>
                        <option value="12">12 Months (Conservative)</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Calculate Target</button>
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

        <div class="grid-3" style="margin-bottom: 2rem;">
            <div class="glass-card">
                <div class="metric-title">Required Target ({{ $fund->recommended_months }} Months)</div>
                <div class="metric-value">₹{{ number_format($required, 2) }}</div>
            </div>
            <div class="glass-card">
                <div class="metric-title">Current Balance</div>
                <div class="metric-value" style="color: var(--accent-primary);">₹{{ number_format($fund->current_amount, 2) }}</div>
            </div>
            <div class="glass-card">
                <div class="metric-title">Remaining Gap</div>
                <div class="metric-value danger">₹{{ number_format($gap, 2) }}</div>
            </div>
        </div>

        <div class="glass-card" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.25rem;">Funding Progress</h3>
                <span style="color: var(--text-secondary); font-weight: 500;">{{ number_format($progress, 1) }}% Funded</span>
            </div>
            
            <div style="width: 100%; height: 24px; background: rgba(0,0,0,0.3); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem;">
                <div style="height: 100%; width: {{ $progress }}%; background: var(--accent-primary); transition: width 1s ease-in-out;"></div>
            </div>

            <p style="color: var(--text-secondary); margin-bottom: 0;">
                <i class="ph ph-info"></i> Your emergency fund currently covers <strong>{{ $coverageMonths }} full months</strong> of living expenses.
            </p>
        </div>

        <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Deposit Card -->
            <div class="glass-card" style="text-align: center; cursor: pointer; transition: all 0.3s ease; border: 2px dashed var(--border);" 
                 onmouseover="this.style.borderColor='var(--success)';" 
                 onmouseout="this.style.borderColor='var(--border)';"
                 onclick="document.getElementById('depositModal').classList.add('active')">
                <i class="ph ph-piggy-bank" style="font-size: 3rem; color: var(--success); margin-bottom: 1rem; display: block;"></i>
                <h3 style="margin-bottom: 0.5rem;">Add Funds</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Deposit money to your emergency fund</p>
            </div>
            
            <!-- Update Plan Card -->
            <div class="glass-card" style="text-align: center; opacity: 0.6;">
                <i class="ph ph-sliders" style="font-size: 3rem; color: var(--text-secondary); margin-bottom: 1rem; display: block;"></i>
                <h3 style="margin-bottom: 0.5rem;">Update Expenses</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Adjust your monthly expense baseline</p>
            </div>
        </div>

        <!-- Deposit Modal -->
        <div id="depositModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Deposit to Emergency Fund</h3>
                    <button class="close-btn" onclick="document.getElementById('depositModal').classList.remove('active')">&times;</button>
                </div>
                <form method="POST" action="{{ route('emergency.deposit') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Deposit Amount (₹)</label>
                        <input type="number" step="1" name="amount" class="form-input" required placeholder="10000">
                    </div>
                    <button type="submit" class="btn-primary btn-success">Record Deposit</button>
                </form>
            </div>
        </div>

    @endif

@endsection
