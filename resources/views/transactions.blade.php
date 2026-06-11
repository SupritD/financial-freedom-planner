@extends('layouts.app')

@section('title', 'Transactions | Financial Freedom Planner')
@section('header', 'All Transactions')

@section('content')
    <div class="flex justify-end gap-4 mb-8">
        <button onclick="document.getElementById('incomeModal').classList.remove('hidden')" class="btn-primary !bg-brand-success hover:!bg-brand-success/90 border-none">+ Add Income</button>
        <button onclick="document.getElementById('expenseModal').classList.remove('hidden')" class="btn-primary !bg-brand-danger hover:!bg-brand-danger/90 border-none">+ Add Expense</button>
    </div>

    @if(session('success'))
        <div class="bg-brand-success/10 text-brand-success p-4 rounded-xl flex justify-between items-center mb-6 border border-brand-success/20">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-xl hover:text-brand-success/80">&times;</button>
        </div>
    @endif

    <div class="glass-card">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="text-xl text-brand-text-primary font-semibold">Transaction History</div>
            
            <button onclick="document.getElementById('filterModal').classList.remove('hidden'); document.getElementById('filterModal').classList.add('flex');" class="bg-brand-surface border border-brand-border hover:border-brand-text-secondary text-brand-text-primary px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2 shadow-sm">
                <i class="ph ph-funnel"></i> Filters
            </button>
        </div>

        @php
            $activeFilters = [];
            if(request('type') && request('type') != 'all') {
                $activeFilters['type'] = ['label' => 'Type', 'value' => ucfirst(request('type'))];
            }
            if(request('category_id')) {
                $catName = collect($incomeSources)->concat($expenseCategories)->firstWhere('id', request('category_id'))->name ?? 'Unknown';
                $activeFilters['category_id'] = ['label' => 'Category', 'value' => $catName];
            }
            if(request('start_date')) {
                $activeFilters['start_date'] = ['label' => 'From', 'value' => request('start_date')];
            }
            if(request('end_date')) {
                $activeFilters['end_date'] = ['label' => 'To', 'value' => request('end_date')];
            }
        @endphp

        @if(count($activeFilters) > 0)
            <div class="flex flex-wrap items-center gap-2 mb-6">
                <span class="text-sm text-brand-text-secondary mr-2">Active Filters:</span>
                @foreach($activeFilters as $key => $filter)
                    <div class="flex items-center gap-1.5 px-3 py-1 bg-brand-accent-primary/20 text-brand-accent-primary border border-brand-accent-primary/30 rounded-full text-xs font-medium">
                        {{ $filter['label'] }}: {{ $filter['value'] }}
                        <button onclick="removeFilter('{{ $key }}')" class="hover:text-white transition-colors flex items-center justify-center">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>
                @endforeach
                <a href="{{ route('transactions') }}" class="text-xs text-brand-text-secondary hover:text-brand-text-primary underline underline-offset-2 ml-2 transition-colors">Clear All</a>
            </div>
            
            <script>
                function removeFilter(key) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete(key);
                    if (key === 'type') {
                        url.searchParams.delete('category_id');
                    }
                    window.location.href = url.toString();
                }
            </script>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="border-b border-brand-border">
                        <th class="px-4 py-4 text-brand-text-secondary font-medium w-16 text-center">#</th>
                        <th class="px-4 py-4 text-brand-text-secondary font-medium">Date</th>
                        <th class="px-4 py-4 text-brand-text-secondary font-medium">Type</th>
                        <th class="px-4 py-4 text-brand-text-secondary font-medium">Details</th>
                        <th class="px-4 py-4 text-brand-text-secondary font-medium text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr class="border-b border-brand-border hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 text-brand-text-secondary text-center">{{ $transactions->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-4 text-brand-text-secondary whitespace-nowrap">{{ $transaction['date'] }}</td>
                            <td class="px-4 py-4">
                                @if($transaction['type'] === 'income')
                                    <span class="bg-brand-success/20 text-brand-success px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">Income</span>
                                @else
                                    <span class="bg-brand-danger/20 text-brand-danger px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">Expense</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-brand-text-primary">{{ $transaction['title'] }}</td>
                            <td class="px-4 py-4 text-right font-semibold {{ $transaction['type'] === 'income' ? 'text-brand-success' : 'text-brand-danger' }}">
                                {{ $transaction['type'] === 'income' ? '+' : '-' }}₹{{ number_format($transaction['amount'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $transactions->links('components.pagination') }}
        </div>
    </div>

    <!-- Income Modal -->
    <div id="incomeModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 hidden">
        <div class="glass-card w-full max-w-md relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Record Income</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl" onclick="document.getElementById('incomeModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('transactions.income.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Date</label>
                    <input type="date" name="income_date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label">Source</label>
                    <select name="source_type_id" class="form-input" required>
                        @foreach($incomeSources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="5000.00">
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn-primary !bg-brand-success hover:!bg-brand-success/90 w-full">Save Income</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Expense Modal -->
    <div id="expenseModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 {{ $errors->has('amount') ? '' : 'hidden' }}">
        <div class="glass-card w-full max-w-md relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Record Expense</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl" onclick="document.getElementById('expenseModal').classList.add('hidden');">&times;</button>
            </div>
            <form method="POST" action="{{ route('transactions.expense.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input" required>
                        @foreach($expenseCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Title / Description</label>
                    <input type="text" name="title" class="form-input" required placeholder="e.g. Weekly Groceries">
                </div>
                <div>
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-input" required placeholder="1200.00">
                    @error('amount')
                        <div class="text-brand-danger text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn-primary !bg-brand-danger hover:!bg-brand-danger/90 w-full">Save Expense</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
        <div class="glass-card w-full max-w-md relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Filter Transactions</h3>
                <button onclick="document.getElementById('filterModal').classList.add('hidden'); document.getElementById('filterModal').classList.remove('flex');" class="text-brand-text-secondary hover:text-white transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('transactions') }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-text-secondary mb-1">Type</label>
                        <select name="type" id="filter-type" class="w-full bg-[#1a1b26] border border-brand-border rounded-lg px-3 py-2 text-sm text-white focus:border-brand-accent-primary focus:ring-1 focus:ring-brand-accent-primary outline-none">
                            <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income Only</option>
                            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense Only</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-brand-text-secondary mb-1">Category</label>
                        <select name="category_id" id="filter-category" class="w-full bg-[#1a1b26] border border-brand-border rounded-lg px-3 py-2 text-sm text-white focus:border-brand-accent-primary focus:ring-1 focus:ring-brand-accent-primary outline-none">
                            <option value="">All Categories</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-text-secondary mb-1">Start Date</label>
                            <input type="date" name="start_date" class="w-full bg-[#1a1b26] border border-brand-border rounded-lg px-3 py-2 text-sm text-white focus:border-brand-accent-primary focus:ring-1 focus:ring-brand-accent-primary outline-none" value="{{ request('start_date') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-text-secondary mb-1">End Date</label>
                            <input type="date" name="end_date" class="w-full bg-[#1a1b26] border border-brand-border rounded-lg px-3 py-2 text-sm text-white focus:border-brand-accent-primary focus:ring-1 focus:ring-brand-accent-primary outline-none" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-brand-text-secondary mb-1">Rows per page</label>
                        <select name="per_page" class="w-full bg-[#1a1b26] border border-brand-border rounded-lg px-3 py-2 text-sm text-white focus:border-brand-accent-primary focus:ring-1 focus:ring-brand-accent-primary outline-none">
                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-8 flex gap-3">
                    <button type="submit" class="flex-1 bg-brand-accent-primary hover:bg-brand-accent-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-lg shadow-brand-accent-primary/20">
                        Apply Filters
                    </button>
                    <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden'); document.getElementById('filterModal').classList.remove('flex');" class="px-4 py-2 border border-brand-border hover:border-brand-text-secondary text-brand-text-primary rounded-lg text-sm font-medium transition-colors bg-brand-surface">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('filter-type');
            const categorySelect = document.getElementById('filter-category');
            
            const incomeSources = [
                @foreach($incomeSources as $src)
                    { id: '{{ $src->id }}', name: '{{ addslashes($src->name) }}' },
                @endforeach
            ];
            
            const expenseCategories = [
                @foreach($expenseCategories as $cat)
                    { id: '{{ $cat->id }}', name: '{{ addslashes($cat->name) }}' },
                @endforeach
            ];

            const currentCategoryId = '{{ request('category_id') }}';

            function updateCategories() {
                const val = typeSelect.value;
                
                categorySelect.innerHTML = '<option value="">All Categories</option>';
                
                let optionsToRender = [];
                
                if (val === 'income') {
                    optionsToRender = incomeSources;
                } else if (val === 'expense') {
                    optionsToRender = expenseCategories;
                } else {
                    optionsToRender = [...incomeSources, ...expenseCategories];
                }
                
                optionsToRender.forEach(function(item) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    if (item.id === currentCategoryId && (val !== 'all' || currentCategoryId)) {
                        opt.selected = true;
                    }
                    categorySelect.appendChild(opt);
                });
            }

            typeSelect.addEventListener('change', function() {
                categorySelect.value = '';
                updateCategories();
            });
            
            updateCategories();
        });
    </script>
@endsection
