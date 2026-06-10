@extends('layouts.app')

@section('title', 'Budgets | Financial Freedom Planner')
@section('header', 'Budget Limits')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color: var(--text-primary); font-size: 1.5rem; font-weight: 600;">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h2>
        <button onclick="document.getElementById('budgetModal').classList.add('active')" class="btn-primary" style="width: auto; padding: 0.75rem 1.5rem;">+ Set Budget Limit</button>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    <div class="grid-3">
        @foreach($budgetData as $data)
            <div class="glass-card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <div style="color: var(--text-primary); font-weight: 600; font-size: 1.125rem;">{{ $data['category']->name }}</div>
                        @if($data['limit'] > 0)
                            <div style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">Limit: ₹{{ number_format($data['limit']) }}</div>
                        @else
                            <div style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">No Limit Set</div>
                        @endif
                    </div>
                    <div style="text-align: right;">
                        <div style="color: {{ $data['is_exceeded'] ? 'var(--danger)' : 'var(--text-primary)' }}; font-weight: 700; font-size: 1.25rem;">
                            ₹{{ number_format($data['spent']) }}
                        </div>
                        <div style="color: var(--text-secondary); font-size: 0.75rem;">Spent</div>
                    </div>
                </div>

                @if($data['limit'] > 0)
                    <div class="progress-bar-container" style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem;">
                        <div class="progress-bar-fill" style="background: {{ $data['is_exceeded'] ? 'var(--danger)' : ($data['progress'] > 80 ? 'var(--warning)' : 'var(--success)') }}; width: {{ $data['progress'] }}%; height: 100%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem;">
                        <span style="color: var(--text-secondary);">{{ number_format($data['progress'], 1) }}% Used</span>
                        @if($data['is_exceeded'])
                            <span style="color: var(--danger);">Exceeded by ₹{{ number_format($data['spent'] - $data['limit']) }}</span>
                        @else
                            <span style="color: var(--success);">₹{{ number_format($data['remaining']) }} Left</span>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Budget Modal -->
    <div id="budgetModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Set Category Budget</h3>
                <button class="close-btn" onclick="document.getElementById('budgetModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('budget.store') }}">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Monthly Limit (₹)</label>
                    <input type="number" step="1" name="amount" class="form-input" required placeholder="5000">
                </div>
                <button type="submit" class="btn-primary">Save Budget</button>
            </form>
        </div>
    </div>
@endsection
