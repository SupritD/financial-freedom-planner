@extends('layouts.app')

@section('title', 'Reports & Analytics | Financial Freedom Planner')
@section('header', 'Reports & Analytics')

@section('content')
    <!-- Filters & Export -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: var(--surface); padding: 1rem 1.5rem; border-radius: 12px; border: 1px solid var(--border);">
        <form method="GET" action="{{ route('reports') }}" style="display: flex; gap: 1rem; align-items: center;">
            <label style="color: var(--text-secondary); font-weight: 500;">Time Period:</label>
            <select name="period" onchange="this.form.submit()" class="form-input" style="width: auto; padding-right: 2rem;">
                <option value="3_months" {{ $period === '3_months' ? 'selected' : '' }}>Last 3 Months</option>
                <option value="6_months" {{ $period === '6_months' ? 'selected' : '' }}>Last 6 Months</option>
                <option value="12_months" {{ $period === '12_months' ? 'selected' : '' }}>Last 12 Months</option>
            </select>
        </form>

        <a href="{{ route('reports.export') }}" class="btn-primary" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
            <i class="ph ph-download-simple"></i> Export CSV
        </a>
    </div>

    <!-- Key Metrics -->
    <div class="grid-3" style="margin-bottom: 2rem;">
        <div class="glass-card">
            <div class="metric-title">Total Income ({{ str_replace('_', ' ', $period) }})</div>
            <div class="metric-value" style="color: var(--success);">₹{{ number_format($totalIncome) }}</div>
        </div>
        <div class="glass-card">
            <div class="metric-title">Total Expenses ({{ str_replace('_', ' ', $period) }})</div>
            <div class="metric-value" style="color: var(--danger);">₹{{ number_format($totalExpense) }}</div>
        </div>
        <div class="glass-card">
            <div class="metric-title">Avg Savings Rate</div>
            <div class="metric-value" style="color: var(--accent-primary);">{{ $savingsRate }}%</div>
        </div>
    </div>

    <!-- Charts -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Income vs Expense Bar Chart -->
        <div class="glass-card">
            <h3 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.25rem;">Income vs Expense</h3>
            <div style="height: 300px; width: 100%;">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Expense by Category Doughnut Chart -->
        <div class="glass-card">
            <h3 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.25rem;">Expenses by Category</h3>
            <div style="height: 300px; display: flex; justify-content: center;">
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
