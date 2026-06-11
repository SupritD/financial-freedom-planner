@extends('layouts.app')

@section('title', 'Admin Dashboard | Financial Freedom Planner')
@section('header', 'Admin Dashboard')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 1rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <i class="ph ph-shield-check" style="font-size: 1.25rem;"></i>
            <strong>Administrator Access</strong> - You are viewing platform-wide data.
        </div>

        <!-- Global Metrics -->
        <div class="grid-4" style="margin-bottom: 2.5rem;">
            <div class="glass-card">
                <div class="metric-title">Total Users</div>
                <div class="metric-value">{{ number_format($totalUsers) }}</div>
            </div>
            <div class="glass-card">
                <div class="metric-title">Active Tenants</div>
                <div class="metric-value">{{ number_format($totalTenants) }}</div>
            </div>
            <div class="glass-card">
                <div class="metric-title">Global Tracked Savings</div>
                <div class="metric-value" style="color: var(--success);">₹{{ number_format($globalSavings) }}</div>
            </div>
            <div class="glass-card">
                <div class="metric-title">Global Tracked Debt</div>
                <div class="metric-value" style="color: var(--danger);">₹{{ number_format($globalDebt) }}</div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="glass-card">
            <h3 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.25rem;">Recent Registrations</h3>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <th style="padding: 1rem 0; color: var(--text-secondary); font-weight: 500;">ID</th>
                            <th style="padding: 1rem 0; color: var(--text-secondary); font-weight: 500;">Name</th>
                            <th style="padding: 1rem 0; color: var(--text-secondary); font-weight: 500;">Email</th>
                            <th style="padding: 1rem 0; color: var(--text-secondary); font-weight: 500;">Status</th>
                            <th style="padding: 1rem 0; color: var(--text-secondary); font-weight: 500;">Registered Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1rem 0; color: var(--text-secondary);">#{{ $user->id }}</td>
                            <td style="padding: 1rem 0; color: var(--text-primary);">
                                {{ $user->name }}
                                @if($user->is_admin)
                                    <span style="background: rgba(168, 85, 247, 0.2); color: #a855f7; padding: 0.1rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;">ADMIN</span>
                                @endif
                            </td>
                            <td style="padding: 1rem 0; color: var(--text-secondary);">{{ $user->email }}</td>
                            <td style="padding: 1rem 0;">
                                @if($user->is_onboarded)
                                    <span style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.85rem;">Active</span>
                                @else
                                    <span style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.85rem;">Pending Onboarding</span>
                                @endif
                            </td>
                            <td style="padding: 1rem 0; color: var(--text-secondary);">{{ $user->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Custom CSS for grid-4 -->
    <style>
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
    </style>
@endsection
