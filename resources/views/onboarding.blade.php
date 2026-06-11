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
</style>

<div class="glass-card max-w-4xl mx-auto">
    
    <div class="flex justify-between mb-8 relative">
        <!-- Progress line -->
        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-white/10 z-0 transform -translate-y-1/2"></div>
        
        <!-- Step Indicators -->
        <div class="w-8 h-8 rounded-full bg-brand-bg-dark border-2 border-brand-accent-primary text-brand-accent-primary flex items-center justify-center relative z-10 font-semibold transition-all duration-300" id="ind-1">1</div>
        <div class="w-8 h-8 rounded-full bg-brand-bg-dark border-2 border-white/20 text-brand-text-secondary flex items-center justify-center relative z-10 font-semibold transition-all duration-300" id="ind-2">2</div>
        <div class="w-8 h-8 rounded-full bg-brand-bg-dark border-2 border-white/20 text-brand-text-secondary flex items-center justify-center relative z-10 font-semibold transition-all duration-300" id="ind-3">3</div>
        <div class="w-8 h-8 rounded-full bg-brand-bg-dark border-2 border-white/20 text-brand-text-secondary flex items-center justify-center relative z-10 font-semibold transition-all duration-300" id="ind-4">4</div>
        <div class="w-8 h-8 rounded-full bg-brand-bg-dark border-2 border-white/20 text-brand-text-secondary flex items-center justify-center relative z-10 font-semibold transition-all duration-300" id="ind-5">5</div>
        <div class="w-8 h-8 rounded-full bg-brand-bg-dark border-2 border-white/20 text-brand-text-secondary flex items-center justify-center relative z-10 font-semibold transition-all duration-300" id="ind-6">6</div>
    </div>

    <form method="POST" action="{{ route('onboarding.store') }}" id="onboardingForm">
        @csrf

        <!-- Step 1: Income -->
        <div class="wizard-step active" id="step-1">
            <h2 class="text-2xl font-semibold text-brand-text-primary mb-6">Income Information</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="form-label">Monthly Salary (₹)</label>
                    <input type="number" name="monthly_salary" class="form-input" placeholder="e.g. 80000">
                </div>
                <div>
                    <label class="form-label">Freelance / Additional Income (₹)</label>
                    <input type="number" name="freelance_income" class="form-input" placeholder="e.g. 15000">
                </div>
                <div>
                    <label class="form-label">Rental Income (₹)</label>
                    <input type="number" name="rental_income" class="form-input" placeholder="e.g. 10000">
                </div>
            </div>
            
            <div class="flex justify-end mt-8">
                <button type="button" class="btn-primary flex items-center gap-2" onclick="nextStep(1)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 2: Expenses -->
        <div class="wizard-step" id="step-2">
            <h2 class="text-2xl font-semibold text-brand-text-primary mb-6">Monthly Expenses</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="form-label">Rent (₹)</label>
                    <input type="number" name="exp_rent" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Food & Groceries (₹)</label>
                    <input type="number" name="exp_food" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Travel & Auto (₹)</label>
                    <input type="number" name="exp_travel" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Utilities (₹)</label>
                    <input type="number" name="exp_utilities" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Shopping (₹)</label>
                    <input type="number" name="exp_shopping" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Others (₹)</label>
                    <input type="number" name="exp_others" class="form-input" placeholder="0">
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" class="px-6 py-3 border border-brand-border text-brand-text-secondary rounded-xl hover:text-brand-text-primary hover:border-brand-text-primary font-semibold transition-colors flex items-center gap-2" onclick="prevStep(2)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-primary flex items-center gap-2" onclick="nextStep(2)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 3: Savings -->
        <div class="wizard-step" id="step-3">
            <h2 class="text-2xl font-semibold text-brand-text-primary mb-6">Current Savings</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="form-label">Bank Savings Balance (₹)</label>
                    <input type="number" name="sav_bank" class="form-input" placeholder="e.g. 50000">
                </div>
                <div>
                    <label class="form-label">Emergency Fund (₹)</label>
                    <input type="number" name="sav_emergency" class="form-input" placeholder="e.g. 100000">
                </div>
                <div>
                    <label class="form-label">Fixed Deposits (₹)</label>
                    <input type="number" name="sav_fd" class="form-input" placeholder="e.g. 200000">
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" class="px-6 py-3 border border-brand-border text-brand-text-secondary rounded-xl hover:text-brand-text-primary hover:border-brand-text-primary font-semibold transition-colors flex items-center gap-2" onclick="prevStep(3)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-primary flex items-center gap-2" onclick="nextStep(3)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 4: Investments -->
        <div class="wizard-step" id="step-4">
            <h2 class="text-2xl font-semibold text-brand-text-primary mb-6">Current Investments</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="form-label">Mutual Funds (₹)</label>
                    <input type="number" name="inv_mf" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Stocks (₹)</label>
                    <input type="number" name="inv_stocks" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">PPF (₹)</label>
                    <input type="number" name="inv_ppf" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">EPF (₹)</label>
                    <input type="number" name="inv_epf" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Gold (₹)</label>
                    <input type="number" name="inv_gold" class="form-input" placeholder="0">
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" class="px-6 py-3 border border-brand-border text-brand-text-secondary rounded-xl hover:text-brand-text-primary hover:border-brand-text-primary font-semibold transition-colors flex items-center gap-2" onclick="prevStep(4)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-primary flex items-center gap-2" onclick="nextStep(4)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 5: Debts -->
        <div class="wizard-step" id="step-5">
            <h2 class="text-2xl font-semibold text-brand-text-primary mb-6">Current Debts (Outstanding)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="form-label">Home Loan (₹)</label>
                    <input type="number" name="debt_home" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Car Loan (₹)</label>
                    <input type="number" name="debt_car" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Personal Loan (₹)</label>
                    <input type="number" name="debt_personal" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Credit Card (₹)</label>
                    <input type="number" name="debt_cc" class="form-input" placeholder="0">
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" class="px-6 py-3 border border-brand-border text-brand-text-secondary rounded-xl hover:text-brand-text-primary hover:border-brand-text-primary font-semibold transition-colors flex items-center gap-2" onclick="prevStep(5)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="button" class="btn-primary flex items-center gap-2" onclick="nextStep(5)">Next Step <i class="ph ph-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 6: Goals -->
        <div class="wizard-step" id="step-6">
            <h2 class="text-2xl font-semibold text-brand-text-primary mb-6">Primary Financial Goal</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="form-label">Goal Name</label>
                    <input type="text" name="goal_name" class="form-input" placeholder="e.g. Buy House in Pune">
                </div>
                <div>
                    <label class="form-label">Target Amount (₹)</label>
                    <input type="number" name="goal_target" class="form-input" placeholder="e.g. 5000000">
                </div>
            </div>
            
            <div class="bg-brand-accent-primary/10 border border-brand-accent-primary/20 rounded-xl p-4 mt-6 flex items-start gap-4">
                <i class="ph-fill ph-info text-brand-accent-primary text-2xl"></i>
                <div class="text-sm text-brand-text-secondary leading-relaxed">
                    Don't worry, you can add more goals, income sources, and precise details later from your dashboard. This is just to get you started!
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" class="px-6 py-3 border border-brand-border text-brand-text-secondary rounded-xl hover:text-brand-text-primary hover:border-brand-text-primary font-semibold transition-colors flex items-center gap-2" onclick="prevStep(6)"><i class="ph ph-arrow-left"></i> Previous</button>
                <button type="submit" class="btn-primary !bg-brand-success hover:!bg-brand-success/90 border-none flex items-center gap-2">Complete Setup <i class="ph ph-check"></i></button>
            </div>
        </div>

    </form>
</div>

<script>
    function nextStep(current) {
        document.getElementById('step-' + current).classList.remove('active');
        document.getElementById('step-' + (current + 1)).classList.add('active');
        
        let currentInd = document.getElementById('ind-' + current);
        currentInd.classList.remove('border-brand-accent-primary', 'text-brand-accent-primary');
        currentInd.classList.add('bg-brand-success', 'border-brand-success', 'text-white');
        currentInd.innerHTML = '<i class="ph ph-check"></i>';
        
        let nextInd = document.getElementById('ind-' + (current + 1));
        nextInd.classList.remove('border-white/20', 'text-brand-text-secondary');
        nextInd.classList.add('border-brand-accent-primary', 'text-brand-accent-primary');
    }

    function prevStep(current) {
        document.getElementById('step-' + current).classList.remove('active');
        document.getElementById('step-' + (current - 1)).classList.add('active');
        
        let currentInd = document.getElementById('ind-' + current);
        currentInd.classList.remove('border-brand-accent-primary', 'text-brand-accent-primary');
        currentInd.classList.add('border-white/20', 'text-brand-text-secondary');
        
        let prevInd = document.getElementById('ind-' + (current - 1));
        prevInd.classList.remove('bg-brand-success', 'border-brand-success', 'text-white');
        prevInd.classList.add('border-brand-accent-primary', 'text-brand-accent-primary');
        prevInd.innerHTML = (current - 1);
    }
</script>
@endsection
