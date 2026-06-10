@extends('layouts.app')

@section('title', 'Debt Management | Financial Freedom Planner')
@section('header', 'Debt Accounts')

@section('content')
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    @error('amount')
        <div style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
    @enderror

    <div class="grid-3">
        @foreach($debts as $debt)
            <div class="glass-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <h3 style="color: var(--text-primary); font-size: 1.25rem; margin-bottom: 0.25rem;">{{ $debt['name'] }}</h3>
                        <span class="badge" style="background: rgba(255,255,255,0.1); color: var(--text-secondary);">{{ $debt['type'] }}</span>
                    </div>
                    @if($debt['is_paid_off'])
                        <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
                    @else
                        <i class="ph ph-bank" style="color: var(--danger); font-size: 1.5rem;"></i>
                    @endif
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <span style="color: var(--text-secondary);">Payoff Progress</span>
                        <span style="color: var(--text-primary); font-weight: 600;">{{ $debt['percentage'] }}%</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.1); border-radius: 99px; overflow: hidden;">
                        <div style="width: {{ $debt['percentage'] }}%; height: 100%; background: var(--success); border-radius: 99px;"></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
                    <div>
                        <div class="metric-title" style="margin-bottom: 0.25rem;">Remaining Balance</div>
                        <div style="font-size: 1.125rem; font-weight: 600; color: var(--danger);">
                            ₹{{ number_format($debt['current_balance']) }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div class="metric-title" style="margin-bottom: 0.25rem;">Interest Rate</div>
                        <div style="color: var(--text-secondary); font-size: 1rem; font-weight: 600;">{{ number_format($debt['interest_rate'], 1) }}% APR</div>
                    </div>
                </div>

                @if(!$debt['is_paid_off'])
                    <button onclick="openPaymentModal('{{ $debt['id'] ?? '' }}', '{{ addslashes($debt['name']) }}')" class="btn-primary" style="width: 100%; padding: 0.75rem; background: var(--success);">Make Payment</button>
                @endif
            </div>
        @endforeach
        <!-- Add New Debt Card -->
        <div onclick="document.getElementById('debtModal').classList.add('active')" class="glass-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 200px; border: 2px dashed var(--border); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--danger)';" onmouseout="this.style.borderColor='var(--border)';">
            <i class="ph ph-plus" style="font-size: 2rem; color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <span style="color: var(--text-secondary); font-weight: 500;">Add Debt Account</span>
        </div>
    </div>

    <!-- Debt Modal -->
    <div id="debtModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Track New Debt</h3>
                <button class="close-btn" onclick="document.getElementById('debtModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('debt.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Debt Name / Lender</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. HDFC Car Loan">
                </div>
                <div class="form-group">
                    <label class="form-label">Debt Type</label>
                    <select name="type" class="form-input" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="personal_loan">Personal Loan</option>
                        <option value="mortgage">Mortgage</option>
                        <option value="auto_loan">Auto Loan</option>
                        <option value="student_loan">Student Loan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Total Outstanding Balance (₹)</label>
                    <input type="number" step="1" name="principal_amount" class="form-input" required placeholder="500000">
                </div>
                <div class="form-group">
                    <label class="form-label">Interest Rate (APR %)</label>
                    <input type="number" step="0.1" name="interest_rate" class="form-input" required placeholder="10.5">
                </div>
                <button type="submit" class="btn-primary btn-danger">Track Debt</button>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="paymentModalTitle">Make Payment</h3>
                <button class="close-btn" onclick="document.getElementById('paymentModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('debt.payment') }}">
                @csrf
                <input type="hidden" name="debt_id" id="paymentDebtId">
                <div class="form-group">
                    <label class="form-label">Payment Amount (₹)</label>
                    <input type="number" step="1" name="amount" class="form-input" required placeholder="5000">
                </div>
                <button type="submit" class="btn-primary btn-success">Submit Payment</button>
            </form>
        </div>
    </div>

    <script>
        function openPaymentModal(id, name) {
            document.getElementById('paymentDebtId').value = id;
            document.getElementById('paymentModalTitle').innerText = 'Make Payment towards ' + name;
            document.getElementById('paymentModal').classList.add('active');
        }
    </script>
@endsection
