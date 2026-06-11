@extends('layouts.app')

@section('title', 'Budgets | Financial Freedom Planner')
@section('header', 'Budget Limits')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <h2 class="text-brand-text-primary text-2xl font-semibold">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h2>
        <button onclick="document.getElementById('budgetModal').classList.remove('hidden')" class="btn-primary w-full sm:w-auto px-6 py-3">
            + Set Budget Limit
        </button>
    </div>

    @if(session('success'))
        <div class="bg-brand-success/20 text-brand-success p-4 rounded-xl mb-6 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-brand-success hover:text-white transition-colors text-xl font-bold">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($budgetData as $data)
            <div class="glass-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-brand-text-primary font-semibold text-lg">{{ $data['category']->name }}</div>
                        @if($data['limit'] > 0)
                            <div class="text-brand-text-secondary text-sm mt-1">Limit: ₹{{ number_format($data['limit']) }}</div>
                        @else
                            <div class="text-brand-text-secondary text-sm mt-1">No Limit Set</div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-xl {{ $data['is_exceeded'] ? 'text-brand-danger' : 'text-brand-text-primary' }}">
                            ₹{{ number_format($data['spent']) }}
                        </div>
                        <div class="text-brand-text-secondary text-xs">Spent</div>
                    </div>
                </div>

                @if($data['limit'] > 0)
                    <div class="w-full bg-white/10 h-2 rounded-full overflow-hidden mb-2">
                        <div class="h-full transition-all duration-500 {{ $data['is_exceeded'] ? 'bg-brand-danger' : ($data['progress'] > 80 ? 'bg-brand-warning' : 'bg-brand-success') }}" style="width: {{ min(100, $data['progress']) }}%;"></div>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-brand-text-secondary">{{ number_format($data['progress'], 1) }}% Used</span>
                        @if($data['is_exceeded'])
                            <span class="text-brand-danger font-medium">Exceeded by ₹{{ number_format($data['spent'] - $data['limit']) }}</span>
                        @else
                            <span class="text-brand-success font-medium">₹{{ number_format($data['remaining']) }} Left</span>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Budget Modal -->
    <div id="budgetModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md transform transition-all shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Set Category Budget</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('budgetModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('budget.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
                
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Monthly Limit (₹)</label>
                    <input type="number" step="1" name="amount" class="form-input" required placeholder="5000">
                </div>
                <button type="submit" class="btn-primary w-full py-3">Save Budget</button>
            </form>
        </div>
    </div>
@endsection
