@extends('layouts.app')

@section('title', 'Savings | Financial Freedom Planner')
@section('header', 'Savings Buckets')

@section('content')
    <div class="grid-3" style="margin-bottom: 2rem;">
        <div class="glass-card" style="grid-column: span 3; text-align: center; padding: 3rem 2rem;">
            <div class="metric-title" style="font-size: 1.25rem;">Total Savings</div>
            <div class="metric-value success" style="font-size: 3rem; margin-top: 1rem;">₹{{ number_format($totalSavings, 2) }}</div>
            <button onclick="document.getElementById('createBucketModal').classList.add('active')" class="btn-primary" style="margin-top: 2rem; width: auto; padding: 0.75rem 2rem;">+ Create New Bucket</button>
        </div>
    </div>

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
        @forelse($savingsBuckets as $bucket)
            <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <i class="ph ph-piggy-bank" style="font-size: 1.5rem; color: var(--accent-primary);"></i>
                        <span style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">{{ $bucket->name }}</span>
                    </div>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem;">
                        ₹{{ number_format($bucket->current_balance) }}
                    </div>
                </div>
                
                <div style="display: flex; gap: 0.5rem;">
                    <button onclick="openTransactionModal('{{ $bucket->id }}', '{{ $bucket->name }}', 'deposit')" class="btn-primary btn-success" style="flex: 1; padding: 0.5rem; font-size: 0.875rem;">Deposit</button>
                    <button onclick="openTransactionModal('{{ $bucket->id }}', '{{ $bucket->name }}', 'withdraw')" class="btn-primary" style="flex: 1; padding: 0.5rem; font-size: 0.875rem; background: rgba(255,255,255,0.1); color: var(--text-primary);">Withdraw</button>
                </div>
            </div>
        @empty
            <div style="grid-column: span 3; text-align: center; color: var(--text-secondary); padding: 3rem;">
                <i class="ph ph-piggy-bank" style="font-size: 4rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i>
                <p>No savings buckets found. Create one to start saving!</p>
            </div>
        @endforelse
    </div>

    <!-- Create Bucket Modal -->
    <div id="createBucketModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create Savings Bucket</h3>
                <button class="close-btn" onclick="document.getElementById('createBucketModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('savings.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Bucket Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. Vacation Fund">
                </div>
                <div class="form-group">
                    <label class="form-label">Initial Amount (₹)</label>
                    <input type="number" step="0.01" name="initial_amount" class="form-input" placeholder="0.00">
                </div>
                <button type="submit" class="btn-primary">Create Bucket</button>
            </form>
        </div>
    </div>

    <!-- Transaction Modal (Deposit / Withdraw) -->
    <div id="transactionModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="transactionModalTitle">Deposit to Bucket</h3>
                <button class="close-btn" onclick="document.getElementById('transactionModal').classList.remove('active')">&times;</button>
            </div>
            <form id="transactionForm" method="POST" action="">
                @csrf
                <input type="hidden" name="account_id" id="transactionAccountId">
                
                <div class="form-group">
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="1000.00">
                </div>
                <button type="submit" id="transactionSubmitBtn" class="btn-primary btn-success">Confirm</button>
            </form>
        </div>
    </div>

    <script>
        function openTransactionModal(accountId, accountName, type) {
            document.getElementById('transactionAccountId').value = accountId;
            const modal = document.getElementById('transactionModal');
            const title = document.getElementById('transactionModalTitle');
            const form = document.getElementById('transactionForm');
            const btn = document.getElementById('transactionSubmitBtn');

            if (type === 'deposit') {
                title.innerText = 'Deposit to ' + accountName;
                form.action = '{{ route("savings.deposit") }}';
                btn.className = 'btn-primary btn-success';
                btn.innerText = 'Deposit';
            } else {
                title.innerText = 'Withdraw from ' + accountName;
                form.action = '{{ route("savings.withdraw") }}';
                btn.className = 'btn-primary';
                btn.style.background = 'var(--danger)';
                btn.innerText = 'Withdraw';
            }

            modal.classList.add('active');
        }
    </script>
@endsection
