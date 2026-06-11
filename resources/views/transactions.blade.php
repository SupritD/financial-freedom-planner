@extends('layouts.app')

@section('title', 'Transactions | Financial Freedom Planner')
@section('header', 'All Transactions')

@section('content')
    <div class="flex justify-end gap-4 mb-8">
        <button onclick="document.getElementById('incomeModal').classList.remove('hidden')" class="btn-primary !bg-brand-success hover:!bg-brand-success/90 border-none">+ Add Income</button>
        <button onclick="document.getElementById('expenseModal').classList.remove('hidden')" class="btn-primary !bg-brand-danger hover:!bg-brand-danger/90 border-none">+ Add Expense</button>
    </div>

    @if(session('success'))
        <div class="bg-brand-success/10 text-brand-success p-4 rounded-xl flex justify-between items-center mb-6 border border-brand-success/20">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-xl hover:text-brand-success/80">&times;</button>
        </div>
    @endif

    <div class="glass-card">
        <div class="text-xl text-brand-text-primary font-semibold mb-6">Transaction History</div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="border-b border-brand-border">
                        <th class="py-4 text-brand-text-secondary font-medium">Date</th>
                        <th class="py-4 text-brand-text-secondary font-medium">Type</th>
                        <th class="py-4 text-brand-text-secondary font-medium">Details</th>
                        <th class="py-4 text-brand-text-secondary font-medium text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr class="border-b border-brand-border hover:bg-white/5 transition-colors">
                            <td class="py-4 text-brand-text-secondary">{{ $transaction['date'] }}</td>
                            <td class="py-4">
                                @if($transaction['type'] === 'income')
                                    <span class="bg-brand-success/20 text-brand-success px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">Income</span>
                                @else
                                    <span class="bg-brand-danger/20 text-brand-danger px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">Expense</span>
                                @endif
                            </td>
                            <td class="py-4 text-brand-text-primary">{{ $transaction['title'] }}</td>
                            <td class="py-4 text-right font-semibold {{ $transaction['type'] === 'income' ? 'text-brand-success' : 'text-brand-danger' }}">
                                {{ $transaction['type'] === 'income' ? '+' : '-' }}₹{{ number_format($transaction['amount'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Income Modal -->
    <div id="incomeModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 hidden">
        <div class="glass-card w-full max-w-md relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Record Income</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl" onclick="document.getElementById('incomeModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('transactions.income.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Date</label>
                    <input type="date" name="income_date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label">Source</label>
                    <select name="source_type_id" class="form-input" required>
                        @foreach($incomeSources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="5000.00">
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn-primary !bg-brand-success hover:!bg-brand-success/90 w-full">Save Income</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Expense Modal -->
    <div id="expenseModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 {{ $errors->has('amount') ? '' : 'hidden' }}">
        <div class="glass-card w-full max-w-md relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Record Expense</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl" onclick="document.getElementById('expenseModal').classList.add('hidden');">&times;</button>
            </div>
            <form method="POST" action="{{ route('transactions.expense.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input" required>
                        @foreach($expenseCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Title / Description</label>
                    <input type="text" name="title" class="form-input" required placeholder="e.g. Weekly Groceries">
                </div>
                <div>
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="1200.00">
                    @error('amount')
                        <div class="text-brand-danger text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn-primary !bg-brand-danger hover:!bg-brand-danger/90 w-full">Save Expense</button>
                </div>
            </form>
        </div>
    </div>
@endsection
