@extends('layouts.app')

@section('title', 'Admin Dashboard | Financial Freedom Planner')
@section('header', 'Admin Dashboard')

@section('content')
    <div class="mb-8">
        <div class="bg-brand-success/10 text-brand-success p-4 rounded-xl inline-flex items-center gap-2 mb-6 border border-brand-success/20">
            <i class="ph ph-shield-check text-xl"></i>
            <span><strong>Administrator Access</strong> - You are viewing platform-wide data.</span>
        </div>

        <!-- Global Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="glass-card flex flex-col justify-center min-h-[140px]">
                <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Total Users</div>
                <div class="text-brand-text-primary text-3xl sm:text-4xl font-bold">{{ number_format($totalUsers) }}</div>
            </div>
            <div class="glass-card flex flex-col justify-center min-h-[140px]">
                <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Active Tenants</div>
                <div class="text-brand-text-primary text-3xl sm:text-4xl font-bold">{{ number_format($totalTenants) }}</div>
            </div>
            <div class="glass-card flex flex-col justify-center min-h-[140px]">
                <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Global Tracked Savings</div>
                <div class="text-brand-success text-3xl sm:text-4xl font-bold">₹{{ number_format($globalSavings) }}</div>
            </div>
            <div class="glass-card flex flex-col justify-center min-h-[140px]">
                <div class="text-brand-text-secondary text-sm uppercase tracking-wider mb-2">Global Tracked Debt</div>
                <div class="text-brand-danger text-3xl sm:text-4xl font-bold">₹{{ number_format($globalDebt) }}</div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="glass-card">
            <h3 class="text-brand-text-primary text-xl font-semibold mb-6">Recent Registrations</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="border-b border-brand-border">
                            <th class="py-4 text-brand-text-secondary font-medium">ID</th>
                            <th class="py-4 text-brand-text-secondary font-medium">Name</th>
                            <th class="py-4 text-brand-text-secondary font-medium">Email</th>
                            <th class="py-4 text-brand-text-secondary font-medium">Status</th>
                            <th class="py-4 text-brand-text-secondary font-medium">Registered Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr class="border-b border-brand-border hover:bg-white/5 transition-colors">
                            <td class="py-4 text-brand-text-secondary">#{{ $user->id }}</td>
                            <td class="py-4 text-brand-text-primary font-medium">
                                {{ $user->name }}
                                @if($user->is_admin)
                                    <span class="bg-[#a855f7]/20 text-[#a855f7] px-2 py-0.5 rounded text-xs ml-2 font-bold tracking-wider">ADMIN</span>
                                @endif
                            </td>
                            <td class="py-4 text-brand-text-secondary">{{ $user->email }}</td>
                            <td class="py-4">
                                @if($user->is_onboarded)
                                    <span class="bg-brand-success/20 text-brand-success px-3 py-1 rounded-full text-sm">Active</span>
                                @else
                                    <span class="bg-[#f59e0b]/20 text-[#f59e0b] px-3 py-1 rounded-full text-sm">Pending Onboarding</span>
                                @endif
                            </td>
                            <td class="py-4 text-brand-text-secondary">{{ $user->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
