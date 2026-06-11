@extends('layouts.app')

@section('title', 'Reports & Analytics | Financial Freedom Planner')
@section('header', 'Reports & Analytics')

@section('content')
    <!-- Filters & Export -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 bg-brand-surface p-4 sm:p-6 rounded-2xl border border-brand-border">
        <form method="GET" action="{{ route('reports') }}" class="flex gap-4 items-center w-full sm:w-auto">
            <label class="text-brand-text-secondary font-medium whitespace-nowrap">Time Period:</label>
            <select name="period" onchange="this.form.submit()" class="form-input w-full sm:w-auto pr-8 py-2 bg-brand-bg-dark border-brand-border cursor-pointer">
                <option value="3_months" {{ $period === '3_months' ? 'selected' : '' }}>Last 3 Months</option>
                <option value="6_months" {{ $period === '6_months' ? 'selected' : '' }}>Last 6 Months</option>
                <option value="12_months" {{ $period === '12_months' ? 'selected' : '' }}>Last 12 Months</option>
            </select>
        </form>

        <a href="{{ route('reports.export') }}" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-2 py-2 px-6 shadow-lg shadow-brand-accent-primary/20 hover:no-underline">
            <i class="ph ph-download-simple text-xl"></i> Export CSV
        </a>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card flex flex-col justify-center min-h-[140px]">
            <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Total Income ({{ str_replace('_', ' ', $period) }})</div>
            <div class="text-brand-success text-3xl sm:text-4xl font-bold">₹{{ number_format($totalIncome) }}</div>
        </div>
        <div class="glass-card flex flex-col justify-center min-h-[140px]">
            <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Total Expenses ({{ str_replace('_', ' ', $period) }})</div>
            <div class="text-brand-danger text-3xl sm:text-4xl font-bold">₹{{ number_format($totalExpense) }}</div>
        </div>
        <div class="glass-card flex flex-col justify-center min-h-[140px]">
            <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Avg Savings Rate</div>
            <div class="text-brand-accent-primary text-3xl sm:text-4xl font-bold">{{ $savingsRate }}%</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Income vs Expense Bar Chart -->
        <div class="glass-card lg:col-span-2">
            <h3 class="text-brand-text-primary text-xl font-semibold mb-6">Income vs Expense</h3>
            <div class="relative w-full h-[300px]">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Expense by Category Doughnut Chart -->
        <div class="glass-card lg:col-span-1">
            <h3 class="text-brand-text-primary text-xl font-semibold mb-6">Expenses by Category</h3>
            <div class="relative w-full h-[300px] flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.font.family = "'Inter', sans-serif";

            // Income vs Expense Chart
            const ctxIE = document.getElementById('incomeExpenseChart').getContext('2d');
            new Chart(ctxIE, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Income',
                            data: {!! json_encode($chartIncome) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.8)', // success color
                            borderRadius: 4
                        },
                        {
                            label: 'Expenses',
                            data: {!! json_encode($chartExpense) !!},
                            backgroundColor: 'rgba(239, 68, 68, 0.8)', // danger color
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255,255,255,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Category Doughnut Chart
            const ctxCat = document.getElementById('categoryChart').getContext('2d');
            
            // Generate distinct colors based on number of categories
            const catColors = [
                'rgba(59, 130, 246, 0.8)',  // blue
                'rgba(16, 185, 129, 0.8)',  // emerald
                'rgba(245, 158, 11, 0.8)',  // amber
                'rgba(239, 68, 68, 0.8)',   // red
                'rgba(168, 85, 247, 0.8)',  // purple
                'rgba(236, 72, 153, 0.8)',  // pink
                'rgba(20, 184, 166, 0.8)'   // teal
            ];

            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pieLabels) !!},
                    datasets: [{
                        data: {!! json_encode($pieData) !!},
                        backgroundColor: catColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
@endsection
