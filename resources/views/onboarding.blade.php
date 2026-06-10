@extends('layouts.app')

@section('title', 'Onboarding | Financial Freedom Planner')
@section('header', 'Welcome! Let\'s setup your financial profile.')

@section('content')
<style>
    .wizard-step {
        display: none;
        animation: fadeIn 0.4s ease forwards;
    }
    .wizard-step.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .wizard-progress {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    .wizard-progress::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: rgba(255,255,255,0.1);
        z-index: 1;
    }
    .step-indicator {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--bg-primary);
        border: 2px solid rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
        color: var(--text-secondary);
        font-weight: 600;
        transition: all 0.3s;
    }
    .step-indicator.completed {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }
    .step-indicator.current {
        border-color: var(--accent-primary);
        color: var(--accent-primary);
    }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem; }
    .form-input {
        width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.2); border: 1px solid var(--border);
        border-radius: 8px; color: var(--text-primary); font-family: inherit; outline: none;
    }
    .form-input:focus { border-color: var(--accent-primary); }
    .btn-group { display: flex; justify-content: space-between; margin-top: 2rem; }
    .btn-next { background: var(--accent-primary); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
    .btn-prev { background: transparent; color: var(--text-secondary); padding: 0.75rem 1.5rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; font-weight: 600; }
</style>

<div class="glass-card" style="max-width: 800px; margin: 0 auto;">
    
    <div class="wizard-progress">
        <div class="step-indicator current" id="ind-1">1</div>
        <div class="step-indicator" id="ind-2">2</div>
        <div class="step-indicator" id="ind-3">3</div>
        <div class="step-indicator" id="ind-4">4</div>
        <div class="step-indicator" id="ind-5">5</div>
        <div class="step-indicator" id="ind-6">6</div>
    </div>

    <form method="POST" action="{{ route('onboarding.store') }}" id="onboardingForm">
        @csrf

        <!-- Step 1: Income -->
        <div class="wizard-step active" id="step-1">
            <h2 style="color: var(--text-primary); margin-bottom: 1.5rem;">Income Information</h2>
            
            <div class="form-group">
                <label class="form-label">Monthly Salary (₹)</label>
                <input type="number" name="monthly_salary" class="form-input" placeholder="e.g. 80000">
            </div>
            <div class="form-group">
                <label class="form-label">Freelance / Additional Income (₹)</label>
                <input type="number" name="freelance_income" class="form-input" placeholder="e.g. 15000">
            </div>
            <div class="form-group">
                <label class="form-label">Rental Income (₹)</label>
                <input type="number" name="rental_income" class="form-input" placeholder="e.g. 10000">
            </div>
            
            <div class="btn-group" style="justify-content: flex-end;">
                <button type="button" class="btn-next" onclick="nextStep(1)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 2: Expenses -->
        <div class="wizard-step" id="step-2">
            <h2 style="color: var(--text-primary); margin-bottom: 1.5rem;">Monthly Expenses</h2>
            
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Rent (₹)</label>
                    <input type="number" name="exp_rent" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Food & Groceries (₹)</label>
                    <input type="number" name="exp_food" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Travel & Auto (₹)</label>
                    <input type="number" name="exp_travel" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Utilities (₹)</label>
                    <input type="number" name="exp_utilities" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Shopping (₹)</label>
                    <input type="number" name="exp_shopping" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Others (₹)</label>
                    <input type="number" name="exp_others" class="form-input" placeholder="0">
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn-prev" onclick="prevStep(2)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-next" onclick="nextStep(2)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 3: Savings -->
        <div class="wizard-step" id="step-3">
            <h2 style="color: var(--text-primary); margin-bottom: 1.5rem;">Current Savings</h2>
            
            <div class="form-group">
                <label class="form-label">Bank Savings Balance (₹)</label>
                <input type="number" name="sav_bank" class="form-input" placeholder="e.g. 50000">
            </div>
            <div class="form-group">
                <label class="form-label">Emergency Fund (₹)</label>
                <input type="number" name="sav_emergency" class="form-input" placeholder="e.g. 100000">
            </div>
            <div class="form-group">
                <label class="form-label">Fixed Deposits (₹)</label>
                <input type="number" name="sav_fd" class="form-input" placeholder="e.g. 200000">
            </div>

            <div class="btn-group">
                <button type="button" class="btn-prev" onclick="prevStep(3)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-next" onclick="nextStep(3)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 4: Investments -->
        <div class="wizard-step" id="step-4">
            <h2 style="color: var(--text-primary); margin-bottom: 1.5rem;">Current Investments</h2>
            
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Mutual Funds (₹)</label>
                    <input type="number" name="inv_mf" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Stocks (₹)</label>
                    <input type="number" name="inv_stocks" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">PPF (₹)</label>
                    <input type="number" name="inv_ppf" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">EPF (₹)</label>
                    <input type="number" name="inv_epf" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Gold (₹)</label>
                    <input type="number" name="inv_gold" class="form-input" placeholder="0">
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn-prev" onclick="prevStep(4)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-next" onclick="nextStep(4)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 5: Debts -->
        <div class="wizard-step" id="step-5">
            <h2 style="color: var(--text-primary); margin-bottom: 1.5rem;">Current Debts (Outstanding)</h2>
            
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Home Loan (₹)</label>
                    <input type="number" name="debt_home" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Car Loan (₹)</label>
                    <input type="number" name="debt_car" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Personal Loan (₹)</label>
                    <input type="number" name="debt_personal" class="form-input" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Credit Card (₹)</label>
                    <input type="number" name="debt_cc" class="form-input" placeholder="0">
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn-prev" onclick="prevStep(5)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-next" onclick="nextStep(5)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 6: Goals -->
        <div class="wizard-step" id="step-6">
            <h2 style="color: var(--text-primary); margin-bottom: 1.5rem;">Primary Financial Goal</h2>
            
            <div class="form-group">
                <label class="form-label">Goal Name</label>
                <input type="text" name="goal_name" class="form-input" placeholder="e.g. Buy House in Pune">
            </div>
            <div class="form-group">
                <label class="form-label">Target Amount (₹)</label>
                <input type="number" name="goal_target" class="form-input" placeholder="e.g. 5000000">
            </div>
            
            <div style="background: rgba(59, 130, 246, 0.1); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 1rem;">
                <i class="ph-fill ph-info" style="color: var(--accent-primary); font-size: 1.5rem;"></i>
                <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                    Don't worry, you can add more goals, income sources, and precise details later from your dashboard. This is just to get you started!
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn-prev" onclick="prevStep(6)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="submit" class="btn-next" style="background: var(--success);">Complete Setup <i class="ph ph-check"></i></button>
            </div>
        </div>

    </form>
</div>

<script>
    function nextStep(current) {
        document.getElementById('step-' + current).classList.remove('active');
        document.getElementById('step-' + (current + 1)).classList.add('active');
        
        document.getElementById('ind-' + current).classList.remove('current');
        document.getElementById('ind-' + current).classList.add('completed');
        document.getElementById('ind-' + current).innerHTML = '<i class="ph ph-check"></i>';
        
        document.getElementById('ind-' + (current + 1)).classList.add('current');
    }

    function prevStep(current) {
        document.getElementById('step-' + current).classList.remove('active');
        document.getElementById('step-' + (current - 1)).classList.add('active');
        
        document.getElementById('ind-' + current).classList.remove('current');
        
        document.getElementById('ind-' + (current - 1)).classList.remove('completed');
        document.getElementById('ind-' + (current - 1)).classList.add('current');
        document.getElementById('ind-' + (current - 1)).innerHTML = (current - 1);
    }
</script>
@endsection
