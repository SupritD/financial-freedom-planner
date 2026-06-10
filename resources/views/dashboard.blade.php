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

    <div class="glass-card" style="margin-bottom: 2rem;">
        <div class="metric-title" style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--text-primary);">6-Month Cashflow</div>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="cashflowChart"></canvas>
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

    <script>
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Income',
                        data: {!! json_encode($chartIncome) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Expenses',
                        data: {!! json_encode($chartExpense) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: '#ef4444',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#f8fafc'
                        }
                    }
                }
            }
        });
    </script>
@endsection
