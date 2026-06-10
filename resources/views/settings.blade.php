@extends('layouts.app')

@section('title', 'Settings | Financial Freedom Planner')
@section('header', 'Profile & Settings')

@section('content')
<div style="display: flex; gap: 2rem;">
    
    <!-- Sidebar Navigation for Settings -->
    <div class="glass-card" style="width: 250px; height: fit-content; padding: 1rem;">
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
            <li><button onclick="switchTab('profile')" class="btn-tab active" id="tab-btn-profile"><i class="ph ph-user"></i> Profile</button></li>
            <li><button onclick="switchTab('security')" class="btn-tab" id="tab-btn-security"><i class="ph ph-lock-key"></i> Security</button></li>
            <li><button onclick="switchTab('2fa')" class="btn-tab" id="tab-btn-2fa"><i class="ph ph-shield-check"></i> Two-Factor Auth</button></li>
            <li><button onclick="switchTab('privacy')" class="btn-tab" id="tab-btn-privacy"><i class="ph ph-lock"></i> Privacy & Data</button></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div style="flex: 1;">
        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <ul style="margin-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Tab -->
        <div id="tab-profile" class="settings-tab active-tab">
            <div class="glass-card">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Personal Information</h3>
                <form method="POST" action="{{ route('settings.profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Email Address (Read Only)</label>
                            <input type="email" class="form-input" value="{{ $user->email }}" disabled style="opacity: 0.6;">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 9876543210">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Base Currency</label>
                            <select name="currency" class="form-input">
                                <option value="INR" {{ $user->currency == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                                <option value="USD" {{ $user->currency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary" style="width: auto;">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="tab-security" class="settings-tab" style="display: none;">
            <div class="glass-card">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Change Password</h3>
                <form method="POST" action="{{ route('settings.password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <button type="submit" class="btn-primary" style="width: auto;">Update Password</button>
                </form>
            </div>
        </div>

        <!-- 2FA Tab -->
        <div id="tab-2fa" class="settings-tab" style="display: none;">
            <div class="glass-card">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Two-Factor Authentication</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
                    Add an extra layer of security to your account. When enabled, you will be required to enter a secure code generated by your authenticator app during login.
                </p>
                
                @if($user->two_factor_secret)
                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                        <i class="ph ph-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
                        <span>Two-Factor Authentication is currently <strong>Enabled</strong>.</span>
                    </div>
                    <form method="POST" action="{{ route('settings.2fa.toggle') }}">
                        @csrf
                        <button type="submit" class="btn-primary btn-danger" style="width: auto;">Disable 2FA</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('settings.2fa.toggle') }}">
                        @csrf
                        <button type="submit" class="btn-primary" style="width: auto;">Enable Two-Factor Auth</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Privacy Tab -->
        <div id="tab-privacy" class="settings-tab" style="display: none;">
            <div class="glass-card" style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Data Export</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
                    You have the right to download a copy of all your financial data stored on our servers. This includes your profile, ledger entries, goals, and debts in JSON format.
                </p>
                <a href="{{ route('settings.export') }}" class="btn-primary" style="width: auto; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <i class="ph ph-download-simple"></i> Download My Data
                </a>
            </div>

            <div class="glass-card" style="border-color: rgba(239, 68, 68, 0.3);">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; color: var(--danger);">Delete Account</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
                    Permanently delete your account and all associated financial data. This action is <strong>irreversible</strong>.
                </p>
                <form method="POST" action="{{ route('settings.account.delete') }}" onsubmit="return confirm('Are you absolutely sure you want to delete your account?');">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="password" class="form-input" required placeholder="Verify your identity">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type "DELETE" to confirm</label>
                        <input type="text" name="delete_confirmation" class="form-input" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn-primary btn-danger" style="width: auto;">Permanently Delete Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-tab {
        width: 100%;
        text-align: left;
        background: transparent;
        border: none;
        color: var(--text-secondary);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
    }
    .btn-tab:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
    }
    .btn-tab.active {
        background: rgba(59, 130, 246, 0.2);
        color: var(--accent-primary);
        font-weight: 500;
    }
</style>

<script>
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.settings-tab').forEach(el => el.style.display = 'none');
        // Remove active class from buttons
        document.querySelectorAll('.btn-tab').forEach(el => el.classList.remove('active'));
        
        // Show selected tab
        document.getElementById('tab-' + tabId).style.display = 'block';
        document.getElementById('tab-btn-' + tabId).classList.add('active');
    }
</script>
@endsection
