@extends('layouts.app')

@section('title', 'Debt Management | Financial Freedom Planner')
@section('header', 'Debt Accounts')

@section('content')
    @if(session('success'))
        <div class="bg-brand-success/20 text-brand-success p-4 rounded-xl mb-6 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-brand-success hover:text-white transition-colors text-xl font-bold">&times;</button>
        </div>
    @endif

    @error('amount')
        <div class="bg-brand-danger/20 text-brand-danger p-4 rounded-xl mb-6 flex justify-between items-center">
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-brand-danger hover:text-white transition-colors text-xl font-bold">&times;</button>
        </div>
    @enderror

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($debts as $debt)
            <div class="glass-card flex flex-col justify-between h-full min-h-[250px]">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-brand-text-primary text-xl font-semibold mb-1">{{ $debt['name'] }}</h3>
                            <span class="inline-block px-2 py-1 rounded bg-white/10 text-brand-text-secondary text-xs uppercase tracking-wider">{{ str_replace('_', ' ', $debt['type']) }}</span>
                        </div>
                        @if($debt['is_paid_off'])
                            <i class="ph-fill ph-check-circle text-brand-success text-3xl"></i>
                        @else
                            <i class="ph ph-bank text-brand-danger text-3xl"></i>
                        @endif
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between mb-2 text-sm">
                            <span class="text-brand-text-secondary">Payoff Progress</span>
                            <span class="text-brand-text-primary font-semibold">{{ $debt['percentage'] }}%</span>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-success rounded-full transition-all duration-500" style="width: {{ $debt['percentage'] }}%;"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <div class="text-brand-text-secondary text-xs uppercase tracking-wider mb-1">Remaining Balance</div>
                            <div class="text-lg font-semibold text-brand-danger">
                                ₹{{ number_format($debt['current_balance']) }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-brand-text-secondary text-xs uppercase tracking-wider mb-1">Interest Rate</div>
                            <div class="text-brand-text-primary font-semibold">{{ number_format($debt['interest_rate'], 1) }}% APR</div>
                        </div>
                    </div>
                </div>

                @if(!$debt['is_paid_off'])
                    <button onclick="openPaymentModal('{{ $debt['id'] ?? '' }}', '{{ addslashes($debt['name']) }}')" class="btn-primary w-full py-3 mt-auto !bg-brand-success hover:!bg-brand-success/90 shadow-lg shadow-brand-success/20">Make Payment</button>
                @endif
            </div>
        @endforeach
        
        <!-- Add New Debt Card -->
        <div onclick="document.getElementById('debtModal').classList.remove('hidden')" class="glass-card flex flex-col items-center justify-center min-h-[250px] border-2 border-dashed border-brand-border cursor-pointer transition-all duration-300 hover:border-brand-danger group">
            <i class="ph ph-plus text-4xl text-brand-text-secondary mb-4 group-hover:text-brand-danger transition-colors"></i>
            <span class="text-brand-text-secondary font-medium group-hover:text-brand-text-primary transition-colors">Add Debt Account</span>
        </div>
    </div>

    <!-- Debt Modal -->
    <div id="debtModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Track New Debt</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('debtModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('debt.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="form-label">Debt Name / Lender</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. HDFC Car Loan">
                </div>
                <div>
                    <label class="form-label">Debt Type</label>
                    <select name="type" class="form-input" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="personal_loan">Personal Loan</option>
                        <option value="mortgage">Mortgage</option>
                        <option value="auto_loan">Auto Loan</option>
                        <option value="student_loan">Student Loan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Total Outstanding Balance (₹)</label>
                    <input type="number" step="1" name="principal_amount" class="form-input" required placeholder="500000">
                </div>
                <div>
                    <label class="form-label">Interest Rate (APR %)</label>
                    <input type="number" step="0.1" name="interest_rate" class="form-input" required placeholder="10.5">
                </div>
                <button type="submit" class="btn-primary w-full py-3 !bg-brand-danger hover:!bg-brand-danger/90 shadow-lg shadow-brand-danger/20">Track Debt</button>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 id="paymentModalTitle" class="text-xl font-semibold text-brand-text-primary">Make Payment</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('paymentModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('debt.payment') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="debt_id" id="paymentDebtId">
                <div>
                    <label class="form-label">Payment Amount (₹)</label>
                    <input type="number" step="1" name="amount" class="form-input" required placeholder="5000">
                </div>
                <button type="submit" class="btn-primary w-full py-3 !bg-brand-success hover:!bg-brand-success/90 shadow-lg shadow-brand-success/20">Submit Payment</button>
            </form>
        </div>
    </div>

    <script>
        function openPaymentModal(id, name) {
            document.getElementById('paymentDebtId').value = id;
            document.getElementById('paymentModalTitle').innerText = 'Make Payment towards ' + name;
            document.getElementById('paymentModal').classList.remove('hidden');
        }
    </script>
@endsection
