@extends('layouts.app')

@section('title', 'Dashboard | Financial Freedom Planner')
@section('header', 'Overview')

@section('content')
    <div class="grid-3">
        <div class="glass-card">
            <div class="metric-title">Total Net Worth</div>
            <div class="metric-value">₹{{ number_format($netWorth, 2) }}</div>
        </div>

        <div class="glass-card">
            <div class="metric-title">Monthly Income</div>
            <div class="metric-value success">+₹{{ number_format($monthlyIncome, 2) }}</div>
        </div>

        <div class="glass-card">
            <div class="metric-title">Monthly Expenses</div>
            <div class="metric-value danger">-₹{{ number_format($monthlyExpense, 2) }}</div>
        </div>
    </div>

    <div class="glass-card">
        <div class="metric-title" style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--text-primary);">Recent Transactions</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Title / Source</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
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
@endsection
