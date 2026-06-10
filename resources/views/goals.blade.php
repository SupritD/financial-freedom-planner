@extends('layouts.app')

@section('title', 'Financial Goals | Financial Freedom Planner')
@section('header', 'Your Financial Goals')

@section('content')
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    @error('amount')
        <div style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
    @enderror

    <div class="grid-3">
        @foreach($goals as $goal)
            <div class="glass-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: var(--text-primary); font-size: 1.25rem;">{{ $goal['name'] }}</h3>
                    @if($goal['is_completed'])
                        <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
                    @else
                        <i class="ph ph-target" style="color: var(--accent-primary); font-size: 1.5rem;"></i>
                    @endif
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <span style="color: var(--text-secondary);">Progress</span>
                        <span style="color: var(--text-primary); font-weight: 600;">{{ $goal['percentage'] }}%</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.1); border-radius: 99px; overflow: hidden;">
                        <div style="width: {{ $goal['percentage'] }}%; height: 100%; background: var(--accent-primary); border-radius: 99px;"></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
                    <div>
                        <div class="metric-title" style="margin-bottom: 0.25rem;">Current / Target</div>
                        <div style="font-size: 1.125rem; font-weight: 600;">
                            ₹{{ number_format($goal['current_amount']) }} <span style="color: var(--text-secondary); font-size: 0.875rem;">/ ₹{{ number_format($goal['target_amount']) }}</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div class="metric-title" style="margin-bottom: 0.25rem;">Deadline</div>
                        <div style="color: var(--text-secondary); font-size: 0.875rem;">{{ $goal['deadline'] }}</div>
                    </div>
                </div>

                @if(!$goal['is_completed'])
                    <button onclick="openContributeModal('{{ $goal['id'] }}', '{{ addslashes($goal['name']) }}')" class="btn-primary" style="width: 100%; padding: 0.75rem;">Contribute</button>
                @endif
            </div>
        @endforeach
        
        <!-- Add New Goal Card -->
        <div onclick="document.getElementById('goalModal').classList.add('active')" class="glass-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 200px; border: 2px dashed var(--border); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--accent-primary)';" onmouseout="this.style.borderColor='var(--border)';">
            <i class="ph ph-plus" style="font-size: 2rem; color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <span style="color: var(--text-secondary); font-weight: 500;">Add New Goal</span>
        </div>
    </div>

    <!-- Contribute Modal -->
    <div id="contributeModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="contributeModalTitle">Contribute to Goal</h3>
                <button class="close-btn" onclick="document.getElementById('contributeModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('goals.contribute') }}">
                @csrf
                <input type="hidden" name="goal_id" id="contributeGoalId">
                <div class="form-group">
                    <label class="form-label">Contribution Amount (₹)</label>
                    <input type="number" step="1" name="amount" class="form-input" required placeholder="5000">
                </div>
                <button type="submit" class="btn-primary">Add Contribution</button>
            </form>
        </div>
    </div>

    <script>
        function openContributeModal(id, name) {
            document.getElementById('contributeGoalId').value = id;
            document.getElementById('contributeModalTitle').innerText = 'Contribute to ' + name;
            document.getElementById('contributeModal').classList.add('active');
        }
    </script>
        
    <!-- Goal Modal -->
    <div id="goalModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create Financial Goal</h3>
                <button class="close-btn" onclick="document.getElementById('goalModal').classList.remove('active')">&times;</button>
            </div>
            <form method="POST" action="{{ route('goals.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Goal Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. Emergency Fund">
                </div>
                <div class="form-group">
                    <label class="form-label">Target Amount (₹)</label>
                    <input type="number" step="1" name="target_amount" class="form-input" required placeholder="100000">
                </div>
                <div class="form-group">
                    <label class="form-label">Deadline (Optional)</label>
                    <input type="date" name="deadline" class="form-input">
                </div>
                <button type="submit" class="btn-primary">Create Goal</button>
            </form>
        </div>
    </div>
@endsection
