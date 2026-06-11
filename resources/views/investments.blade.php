@extends('layouts.app')

@section('title', 'Investments | Financial Freedom Planner')
@section('header', 'Investment Portfolio')

@section('content')
    <div class="grid-3" style="margin-bottom: 2rem;">
        <div class="glass-card" style="grid-column: span 3; text-align: center; padding: 3rem 2rem;">
            <div class="metric-title" style="font-size: 1.25rem;">Total Portfolio Value</div>
            <div class="metric-value" style="color: var(--accent-primary); font-size: 3rem; margin-top: 1rem;">₹{{ number_format($totalInvestments, 2) }}</div>
            <button onclick="document.getElementById('createInvestmentModal').classList.add('active')" class="btn-primary" style="margin-top: 2rem; width: auto; padding: 0.75rem 2rem;">+ Track New Investment</button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
    @endif
    
    @error('new_value')
        <div style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
    @enderror

    <div class="grid-3">
        @forelse($investments as $investment)
            <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <i class="ph ph-trend-up" style="font-size: 1.5rem; color: var(--accent-primary);"></i>
                        <span style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">{{ $investment->name }}</span>
                    </div>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem;">
                        ₹{{ number_format($investment->current_balance) }}
                    </div>
                </div>
                
                <button onclick="openUpdateModal('{{ $investment->id }}', '{{ addslashes($investment->name) }}', '{{ $investment->current_balance }}')" class="btn-primary" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.1); color: var(--text-primary);">Update Value</button>
            </div>
        @empty
            <div style="grid-column: span 3; text-align: center; color: var(--text-secondary); padding: 3rem;">
                <i class="ph ph-chart-line-up" style="font-size: 4rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i>
                <p>No investments tracked. Add an asset to start monitoring your portfolio!</p>
            </div>
        @endforelse
    </div>

    <!-- Create Investment Modal -->
    <div id="createInvestmentModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Track New Investment</h3>
                <button class="close-btn" onclick="document.getElementById('createInvestmentModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('investments.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Asset Name / Ticker</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. NIFTY 50 Index Fund">
                </div>
                <div class="form-group">
                    <label class="form-label">Current Value (₹)</label>
                    <input type="number" step="0.01" name="initial_value" class="form-input" placeholder="0.00">
                </div>
                <button type="submit" class="btn-primary">Track Investment</button>
            </form>
        </div>
    </div>

    <!-- Update Value Modal -->
    <div id="updateModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="updateModalTitle">Update Value</h3>
                <button class="close-btn" onclick="document.getElementById('updateModal').classList.remove('active')">&times;</button>
            </div>
            <form id="updateForm" method="POST" action="{{ route('investments.update') }}">
                @csrf
                <input type="hidden" name="account_id" id="updateAccountId">
                
                <div class="form-group">
                    <label class="form-label">New Current Value (₹)</label>
                    <input type="number" step="0.01" name="new_value" id="updateNewValue" class="form-input" required placeholder="1000.00">
                </div>
                <button type="submit" class="btn-primary">Save Updated Value</button>
            </form>
        </div>
    </div>

    <script>
        function openUpdateModal(accountId, accountName, currentValue) {
            document.getElementById('updateAccountId').value = accountId;
            document.getElementById('updateNewValue').value = currentValue;
            document.getElementById('updateModalTitle').innerText = 'Update ' + accountName;
            document.getElementById('updateModal').classList.add('active');
        }
    </script>
@endsection
