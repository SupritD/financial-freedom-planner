@extends('layouts.app')

@section('title', 'Dashboard | Financial Freedom Planner')
@section('header', 'Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card">
            <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Total Net Worth</div>
            <div class="text-4xl font-bold">₹{{ number_format($netWorth, 2) }}</div>
        </div>

        <div class="glass-card">
            <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Monthly Income</div>
            <div class="text-4xl font-bold text-brand-success">+₹{{ number_format($monthlyIncome, 2) }}</div>
        </div>

        <div class="glass-card">
            <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Monthly Expenses</div>
            <div class="text-4xl font-bold text-brand-danger">-₹{{ number_format($monthlyExpense, 2) }}</div>
        </div>
    </div>

    <div class="glass-card mb-8">
        <div class="text-xl text-brand-text-primary mb-6">6-Month Cashflow</div>
        <div class="relative w-full h-[300px]">
            <canvas id="cashflowChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Goal Progress -->
        <div class="glass-card">
            <div class="text-xl text-brand-text-primary mb-6">Active Goals</div>
            @if($goals->count() > 0)
                @foreach($goals as $goal)
                    @php
                        $progress = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-brand-text-primary font-medium">{{ $goal->name }}</span>
                            <span class="text-brand-text-secondary text-sm">{{ number_format($progress, 1) }}%</span>
                        </div>
                        <div class="w-full bg-white/10 h-2 rounded-full overflow-hidden">
                            <div class="bg-brand-accent-primary h-full transition-all duration-500" style="width: {{ min(100, $progress) }}%;"></div>
                        </div>
                        <div class="text-right text-brand-text-secondary text-xs mt-1">
                            ₹{{ number_format($goal->current_amount) }} / ₹{{ number_format($goal->target_amount) }}
                        </div>
                    </div>
                @endforeach
                <div class="text-center mt-4">
                    <a href="{{ route('goals') }}" class="text-brand-accent-primary hover:text-brand-accent-primary/80 text-sm font-medium transition-colors">View All Goals →</a>
                </div>
            @else
                <p class="text-brand-text-secondary">No active goals. <a href="{{ route('goals') }}" class="text-brand-accent-primary hover:underline">Create one</a>.</p>
            @endif
        </div>

        <!-- Emergency Fund Gap -->
        <div class="glass-card">
            <div class="text-xl text-brand-text-primary mb-6">Emergency Fund Status</div>
            @if($emergencyFund)
                @php
                    $efProgress = $emergencyFundTarget > 0 ? ($emergencyFundCurrent / $emergencyFundTarget) * 100 : 0;
                @endphp
                <div class="text-center mb-6">
                    <div class="text-3xl font-bold {{ $emergencyFundGap > 0 ? 'text-brand-warning' : 'text-brand-success' }}">
                        ₹{{ number_format($emergencyFundGap) }}
                    </div>
                    <div class="text-brand-text-secondary text-sm mt-1">Remaining Gap</div>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-brand-text-primary">Fund Progress</span>
                        <span class="text-brand-text-secondary text-sm">{{ number_format($efProgress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-white/10 h-2 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-500 {{ $emergencyFundGap > 0 ? 'bg-brand-warning' : 'bg-brand-success' }}" style="width: {{ min(100, $efProgress) }}%;"></div>
                    </div>
                </div>
                
                <div class="flex justify-between text-brand-text-secondary text-sm">
                    <span>Current: ₹{{ number_format($emergencyFundCurrent) }}</span>
                    <span>Target: ₹{{ number_format($emergencyFundTarget) }}</span>
                </div>
            @else
                <p class="text-brand-text-secondary">No emergency fund configured. <a href="{{ route('emergency') }}" class="text-brand-accent-primary hover:underline">Setup now</a>.</p>
            @endif
        </div>
    </div>

    <div class="glass-card overflow-x-auto">
        <div class="text-xl text-brand-text-primary mb-6">Recent Transactions</div>
        
        <table class="w-full text-left border-collapse min-w-[600px]">
            <thead>
                <tr class="border-b border-brand-border">
                    <th class="py-3 px-4 text-brand-text-secondary font-medium text-sm">Date</th>
                    <th class="py-3 px-4 text-brand-text-secondary font-medium text-sm">Type</th>
                    <th class="py-3 px-4 text-brand-text-secondary font-medium text-sm">Title / Source</th>
                    <th class="py-3 px-4 text-brand-text-secondary font-medium text-sm">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                    <tr class="border-b border-brand-border/50 hover:bg-white/5 transition-colors">
                        <td class="py-4 px-4 text-brand-text-secondary text-sm">{{ $transaction['date'] }}</td>
                        <td class="py-4 px-4">
                            @if($transaction['type'] === 'income')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-success/20 text-brand-success">Income</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-danger/20 text-brand-danger">Expense</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-brand-text-primary">{{ $transaction['title'] }}</td>
                        <td class="py-4 px-4 font-semibold {{ $transaction['type'] === 'income' ? 'text-brand-success' : 'text-brand-danger' }}">
                            {{ $transaction['type'] === 'income' ? '+' : '-' }}₹{{ number_format($transaction['amount'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
@endsection
