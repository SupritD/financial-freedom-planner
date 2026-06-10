@extends('layouts.app')

@section('title', 'Transactions | Financial Freedom Planner')
@section('header', 'All Transactions')

@section('content')
    <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-bottom: 2rem;">
        <button onclick="document.getElementById('incomeModal').classList.add('active')" class="btn-primary btn-success" style="width: auto; padding: 0.75rem 1.5rem;">+ Add Income</button>
        <button onclick="document.getElementById('expenseModal').classList.add('active')" class="btn-primary btn-danger" style="width: auto; padding: 0.75rem 1.5rem;">+ Add Expense</button>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card">
        <div class="metric-title" style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--text-primary);">Transaction History</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Details</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td style="color: var(--text-secondary)">{{ $transaction['date'] }}</td>
                        <td>
                            @if($transaction['type'] === 'income')
                                <span class="badge badge-income">Income</span>
                            @else
                                <span class="badge badge-expense">Expense</span>
                            @endif
                        </td>
                        <td>{{ $transaction['title'] }}</td>
                        <td class="{{ $transaction['type'] === 'income' ? 'success' : 'danger' }}" style="font-weight: 600;">
                            {{ $transaction['type'] === 'income' ? '+' : '-' }}₹{{ number_format($transaction['amount'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Income Modal -->
    <div id="incomeModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Record Income</h3>
                <button class="close-btn" onclick="document.getElementById('incomeModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('transactions.income.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="income_date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Source</label>
                    <select name="source_type_id" class="form-input" required>
                        @foreach($incomeSources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="5000.00">
                </div>
                <button type="submit" class="btn-primary btn-success">Save Income</button>
            </form>
        </div>
    </div>

    <!-- Expense Modal -->
    <div id="expenseModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Record Expense</h3>
                <button class="close-btn" onclick="document.getElementById('expenseModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('transactions.expense.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input" required>
                        @foreach($expenseCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Title / Description</label>
                    <input type="text" name="title" class="form-input" required placeholder="e.g. Weekly Groceries">
                </div>
                <div class="form-group">
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="1200.00">
                </div>
                <button type="submit" class="btn-primary btn-danger">Save Expense</button>
            </form>
        </div>
    </div>
@endsection
