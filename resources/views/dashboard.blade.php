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

    <div class="grid-2">
        <!-- Goal Progress -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <div class="metric-title" style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--text-primary);">Active Goals</div>
            @if($goals->count() > 0)
                @foreach($goals as $goal)
                    @php
                        $progress = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                    @endphp
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-primary); font-weight: 500;">{{ $goal->name }}</span>
                            <span style="color: var(--text-secondary); font-size: 0.875rem;">{{ number_format($progress, 1) }}%</span>
                        </div>
                        <div class="progress-bar-container" style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div class="progress-bar-fill" style="background: var(--accent-primary); width: {{ min(100, $progress) }}%; height: 100%;"></div>
                        </div>
                        <div style="text-align: right; color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                            ₹{{ number_format($goal->current_amount) }} / ₹{{ number_format($goal->target_amount) }}
                        </div>
                    </div>
                @endforeach
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="{{ route('goals') }}" style="color: var(--accent-primary); text-decoration: none; font-size: 0.875rem;">View All Goals →</a>
                </div>
            @else
                <p style="color: var(--text-secondary);">No active goals. <a href="{{ route('goals') }}" style="color: var(--accent-primary);">Create one</a>.</p>
            @endif
        </div>

        <!-- Emergency Fund Gap -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <div class="metric-title" style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--text-primary);">Emergency Fund Status</div>
            @if($emergencyFund)
                @php
                    $efProgress = $emergencyFundTarget > 0 ? ($emergencyFundCurrent / $emergencyFundTarget) * 100 : 0;
                @endphp
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="font-size: 2rem; font-weight: 700; color: {{ $emergencyFundGap > 0 ? 'var(--warning)' : 'var(--success)' }};">
                        ₹{{ number_format($emergencyFundGap) }}
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.875rem;">Remaining Gap</div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-primary);">Fund Progress</span>
                        <span style="color: var(--text-secondary); font-size: 0.875rem;">{{ number_format($efProgress, 1) }}%</span>
                    </div>
                    <div class="progress-bar-container" style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                        <div class="progress-bar-fill" style="background: {{ $emergencyFundGap > 0 ? 'var(--warning)' : 'var(--success)' }}; width: {{ min(100, $efProgress) }}%; height: 100%;"></div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; color: var(--text-secondary); font-size: 0.875rem;">
                    <span>Current: ₹{{ number_format($emergencyFundCurrent) }}</span>
                    <span>Target: ₹{{ number_format($emergencyFundTarget) }}</span>
                </div>
            @else
                <p style="color: var(--text-secondary);">No emergency fund configured. <a href="{{ route('emergency') }}" style="color: var(--accent-primary);">Setup now</a>.</p>
            @endif
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
