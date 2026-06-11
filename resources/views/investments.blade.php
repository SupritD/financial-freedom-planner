@extends('layouts.app')

@section('title', 'Investments | Financial Freedom Planner')
@section('header', 'Investment Portfolio')

@section('content')
    <div class="mb-8">
        <div class="glass-card text-center py-12 px-6">
            <div class="text-brand-text-secondary text-lg uppercase tracking-wider">Total Portfolio Value</div>
            <div class="text-brand-accent-primary text-5xl font-bold mt-4">₹{{ number_format($totalInvestments, 2) }}</div>
            <button onclick="document.getElementById('createInvestmentModal').classList.remove('hidden')" class="btn-primary mt-8 px-8 py-3 w-full sm:w-auto">
                + Track New Investment
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-brand-success/20 text-brand-success p-4 rounded-xl mb-6 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-brand-success hover:text-white transition-colors text-xl font-bold">&times;</button>
        </div>
    @endif
    
    @error('new_value')
        <div class="bg-brand-danger/20 text-brand-danger p-4 rounded-xl mb-6 flex justify-between items-center">
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-brand-danger hover:text-white transition-colors text-xl font-bold">&times;</button>
        </div>
    @enderror

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($investments as $investment)
            <div class="glass-card flex flex-col justify-between h-full min-h-[200px]">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="ph ph-trend-up text-2xl text-brand-accent-primary"></i>
                        <span class="text-xl font-semibold text-brand-text-primary">{{ $investment->name }}</span>
                    </div>
                    <div class="text-3xl font-bold text-brand-text-primary mb-6">
                        ₹{{ number_format($investment->current_balance) }}
                    </div>
                </div>
                
                <button onclick="openUpdateModal('{{ $investment->id }}', '{{ addslashes($investment->name) }}', '{{ $investment->current_balance }}')" class="w-full bg-white/10 text-brand-text-primary py-3 rounded-lg font-medium hover:bg-white/20 transition-colors mt-auto">Update Value</button>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center text-brand-text-secondary py-12">
                <i class="ph ph-chart-line-up text-6xl opacity-50 mb-4 block"></i>
                <p>No investments tracked. Add an asset to start monitoring your portfolio!</p>
            </div>
        @endforelse
    </div>

    <!-- Create Investment Modal -->
    <div id="createInvestmentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Track New Investment</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('createInvestmentModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('investments.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="form-label">Asset Name / Ticker</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. NIFTY 50 Index Fund">
                </div>
                <div>
                    <label class="form-label">Current Value (₹)</label>
                    <input type="number" step="0.01" name="initial_value" class="form-input" placeholder="0.00">
                </div>
                <button type="submit" class="btn-primary w-full py-3">Track Investment</button>
            </form>
        </div>
    </div>

    <!-- Update Value Modal -->
    <div id="updateModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 id="updateModalTitle" class="text-xl font-semibold text-brand-text-primary">Update Value</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('updateModal').classList.add('hidden')">&times;</button>
            </div>
            <form id="updateForm" method="POST" action="{{ route('investments.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="account_id" id="updateAccountId">
                
                <div>
                    <label class="form-label">New Current Value (₹)</label>
                    <input type="number" step="0.01" name="new_value" id="updateNewValue" class="form-input" required placeholder="1000.00">
                </div>
                <button type="submit" class="btn-primary w-full py-3">Save Updated Value</button>
            </form>
        </div>
    </div>

    <script>
        function openUpdateModal(accountId, accountName, currentValue) {
            document.getElementById('updateAccountId').value = accountId;
            document.getElementById('updateNewValue').value = currentValue;
            document.getElementById('updateModalTitle').innerText = 'Update ' + accountName;
            document.getElementById('updateModal').classList.remove('hidden');
        }
    </script>
@endsection
