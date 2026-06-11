@extends('layouts.app')

@section('title', 'Financial Goals | Financial Freedom Planner')
@section('header', 'Your Financial Goals')

@section('content')
    @if(session('success'))
        <div class="bg-brand-success/20 text-brand-success p-4 rounded-xl mb-6 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-brand-success hover:text-white transition-colors text-xl font-bold">&times;</button>
        </div>
    @endif

    @error('amount')
        <div class="bg-brand-danger/20 text-brand-danger p-4 rounded-xl mb-6 flex justify-between items-center">
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-brand-danger hover:text-white transition-colors text-xl font-bold">&times;</button>
        </div>
    @enderror

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($goals as $goal)
            <div class="glass-card flex flex-col justify-between h-full min-h-[250px]">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-brand-text-primary text-xl font-semibold">{{ $goal['name'] }}</h3>
                        @if($goal['is_completed'])
                            <i class="ph-fill ph-check-circle text-brand-success text-2xl"></i>
                        @else
                            <i class="ph ph-target text-brand-accent-primary text-2xl"></i>
                        @endif
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between mb-2 text-sm">
                            <span class="text-brand-text-secondary">Progress</span>
                            <span class="text-brand-text-primary font-semibold">{{ $goal['percentage'] }}%</span>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-accent-primary rounded-full transition-all duration-500" style="width: {{ $goal['percentage'] }}%;"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <div class="text-brand-text-secondary text-xs uppercase tracking-wider mb-1">Current / Target</div>
                            <div class="text-lg font-semibold text-brand-text-primary">
                                ₹{{ number_format($goal['current_amount']) }} <span class="text-brand-text-secondary text-sm">/ ₹{{ number_format($goal['target_amount']) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-brand-text-secondary text-xs uppercase tracking-wider mb-1">Deadline</div>
                            <div class="text-brand-text-secondary text-sm">{{ $goal['deadline'] }}</div>
                        </div>
                    </div>
                </div>

                @if(!$goal['is_completed'])
                    <button onclick="openContributeModal('{{ $goal['id'] }}', '{{ addslashes($goal['name']) }}')" class="btn-primary w-full py-3 mt-auto">Contribute</button>
                @endif
            </div>
        @endforeach
        
        <!-- Add New Goal Card -->
        <div onclick="document.getElementById('goalModal').classList.remove('hidden')" class="glass-card flex flex-col items-center justify-center min-h-[250px] border-2 border-dashed border-brand-border cursor-pointer transition-all duration-300 hover:border-brand-accent-primary group">
            <i class="ph ph-plus text-4xl text-brand-text-secondary mb-4 group-hover:text-brand-accent-primary transition-colors"></i>
            <span class="text-brand-text-secondary font-medium group-hover:text-brand-text-primary transition-colors">Add New Goal</span>
        </div>
    </div>

    <!-- Contribute Modal -->
    <div id="contributeModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 id="contributeModalTitle" class="text-xl font-semibold text-brand-text-primary">Contribute to Goal</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('contributeModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('goals.contribute') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="goal_id" id="contributeGoalId">
                <div>
                    <label class="form-label">Contribution Amount (₹)</label>
                    <input type="number" step="1" name="amount" class="form-input" required placeholder="5000">
                </div>
                <button type="submit" class="btn-primary w-full py-3">Add Contribution</button>
            </form>
        </div>
    </div>

    <script>
        function openContributeModal(id, name) {
            document.getElementById('contributeGoalId').value = id;
            document.getElementById('contributeModalTitle').innerText = 'Contribute to ' + name;
            document.getElementById('contributeModal').classList.remove('hidden');
        }
    </script>
        
    <!-- Goal Modal -->
    <div id="goalModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-border rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-brand-text-primary">Create Financial Goal</h3>
                <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('goalModal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('goals.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="form-label">Goal Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. Emergency Fund">
                </div>
                <div>
                    <label class="form-label">Target Amount (₹)</label>
                    <input type="number" step="1" name="target_amount" class="form-input" required placeholder="100000">
                </div>
                <div>
                    <label class="form-label">Deadline (Optional)</label>
                    <input type="date" name="deadline" class="form-input" style="color-scheme: dark;">
                </div>
                <button type="submit" class="btn-primary w-full py-3">Create Goal</button>
            </form>
        </div>
    </div>
@endsection
