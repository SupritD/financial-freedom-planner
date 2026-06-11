@extends('layouts.app')

@section('title', 'Savings | Financial Freedom Planner')
@section('header', 'Savings Buckets')

@section('content')
    <div class="mb-8">
        <div class="glass-card text-center py-12 px-6">
            <div class="text-brand-text-secondary text-lg uppercase tracking-wider">Total Savings</div>
            <div class="text-brand-success text-5xl font-bold mt-4">₹{{ number_format($totalSavings, 2) }}</div>
            <button onclick="document.getElementById('createBucketModal').classList.remove('hidden')" class="btn-primary mt-8 px-8 py-3 w-full sm:w-auto">
                + Create New Bucket
            </button>
        </div>
    </div>

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
        @forelse($savingsBuckets as $bucket)
            <div class="glass-card flex flex-col justify-between h-full min-h-[200px]">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="ph ph-piggy-bank text-2xl text-brand-accent-primary"></i>
                        <span class="text-xl font-semibold text-brand-text-primary">{{ $bucket->name }}</span>
                    </div>
                    <div class="text-3xl font-bold text-brand-text-primary mb-6">
                        ₹{{ number_format($bucket->current_balance) }}
                    </div>
                </div>
                
                <div class="flex gap-2 mt-auto">
                    <button onclick="openTransactionModal('{{ $bucket->id }}', '{{ $bucket->name }}', 'deposit')" class="flex-1 bg-brand-success text-white py-2 rounded-lg font-medium hover:bg-brand-success/90 transition-colors shadow-lg shadow-brand-success/20">Deposit</button>
                    <button onclick="openTransactionModal('{{ $bucket->id }}', '{{ $bucket->name }}', 'withdraw')" class="flex-1 bg-white/10 text-brand-text-primary py-2 rounded-lg font-medium hover:bg-white/20 transition-colors">Withdraw</button>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center text-brand-text-secondary py-12">
                <i class="ph ph-piggy-bank text-6xl opacity-50 mb-4 block"></i>
                <p>No savings buckets found. Create one to start saving!</p>
            </div>
        @endforelse
    </div>

    <!-- Create Bucket Modal -->
    <div id="createBucketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Create Savings Bucket</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('createBucketModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('savings.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="form-label">Bucket Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. Vacation Fund">
                </div>
                <div>
                    <label class="form-label">Initial Amount (₹)</label>
                    <input type="number" step="0.01" name="initial_amount" class="form-input" placeholder="0.00">
                </div>
                <button type="submit" class="btn-primary w-full py-3">Create Bucket</button>
            </form>
        </div>
    </div>

    <!-- Transaction Modal (Deposit / Withdraw) -->
    <div id="transactionModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 id="transactionModalTitle" class="text-xl font-semibold text-brand-text-primary">Deposit to Bucket</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('transactionModal').classList.add('hidden')">&times;</button>
            </div>
            <form id="transactionForm" method="POST" action="" class="space-y-6">
                @csrf
                <input type="hidden" name="account_id" id="transactionAccountId">
                
                <div>
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="1000.00">
                </div>
                <button type="submit" id="transactionSubmitBtn" class="w-full py-3 rounded-lg font-medium text-white transition-colors">Confirm</button>
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
                btn.className = 'w-full py-3 rounded-lg font-medium text-white transition-colors bg-brand-success hover:bg-brand-success/90 shadow-lg shadow-brand-success/20';
                btn.innerText = 'Deposit';
            } else {
                title.innerText = 'Withdraw from ' + accountName;
                form.action = '{{ route("savings.withdraw") }}';
                btn.className = 'w-full py-3 rounded-lg font-medium text-white transition-colors bg-brand-danger hover:bg-brand-danger/90 shadow-lg shadow-brand-danger/20';
                btn.innerText = 'Withdraw';
            }

            modal.classList.remove('hidden');
        }
    </script>
@endsection
