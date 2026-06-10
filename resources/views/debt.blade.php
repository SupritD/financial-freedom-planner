@extends('layouts.app')

@section('title', 'Debt Management | Financial Freedom Planner')
@section('header', 'Debt Accounts')

@section('content')
    <div class="grid-3">
        @foreach($debts as $debt)
            <div class="glass-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <h3 style="color: var(--text-primary); font-size: 1.25rem; margin-bottom: 0.25rem;">{{ $debt['name'] }}</h3>
                        <span class="badge" style="background: rgba(255,255,255,0.1); color: var(--text-secondary);">{{ $debt['type'] }}</span>
                    </div>
                    @if($debt['is_paid_off'])
                        <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
                    @else
                        <i class="ph ph-bank" style="color: var(--danger); font-size: 1.5rem;"></i>
                    @endif
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <span style="color: var(--text-secondary);">Payoff Progress</span>
                        <span style="color: var(--text-primary); font-weight: 600;">{{ $debt['percentage'] }}%</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.1); border-radius: 99px; overflow: hidden;">
                        <div style="width: {{ $debt['percentage'] }}%; height: 100%; background: var(--success); border-radius: 99px;"></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <div class="metric-title" style="margin-bottom: 0.25rem;">Remaining Balance</div>
                        <div style="font-size: 1.125rem; font-weight: 600; color: var(--danger);">
                            ₹{{ number_format($debt['current_balance']) }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div class="metric-title" style="margin-bottom: 0.25rem;">Interest Rate</div>
                        <div style="color: var(--text-secondary); font-size: 1rem; font-weight: 600;">{{ number_format($debt['interest_rate'], 1) }}% APR</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
