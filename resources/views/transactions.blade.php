@extends('layouts.app')

@section('title', 'Transactions | Financial Freedom Planner')
@section('header', 'All Transactions')

@section('content')
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
@endsection
