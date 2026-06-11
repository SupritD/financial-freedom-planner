@extends('layouts.app')

@section('title', 'Profile Settings | Financial Freedom Planner')
@section('header', 'Profile Settings')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">

        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: inherit; font-size: 1.25rem; cursor: pointer;">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Information & Security -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <h3 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.25rem;">Account Information & Security</h3>
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    </div>
                </div>

                <div style="margin: 1.5rem 0; border-top: 1px solid var(--border);"></div>

                <h4 style="color: var(--text-primary); margin-bottom: 1rem;">Update Password</h4>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" placeholder="Leave blank to keep current">
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-input">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-input">
                    </div>
                </div>

                <div style="text-align: right; margin-top: 1rem;">
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.25rem;">Two-Factor Authentication</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem;">Add additional security to your account using two-factor authentication.</p>

            @if(empty($user->two_factor_secret))
                @if($qrCode)
                    <div style="background: var(--surface); padding: 1.5rem; border-radius: 12px; text-align: center; margin-bottom: 1.5rem;">
                        <p style="color: var(--text-primary); margin-bottom: 1rem;">Scan this QR code with your authenticator application (e.g., Google Authenticator, Authy).</p>
                        <div style="background: white; padding: 1rem; display: inline-block; border-radius: 8px;">
                            {!! $qrCode !!}
                        </div>
                        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 1rem; font-family: monospace;">Setup Key: {{ $secret }}</p>
                    </div>

                    <form method="POST" action="{{ route('profile.2fa.confirm') }}" style="max-width: 400px;">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Verify Code</label>
                            <input type="text" name="code" class="form-input" placeholder="Enter 6-digit code" required>
                        </div>
                        <button type="submit" class="btn-primary">Confirm & Enable 2FA</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('profile.2fa.enable') }}">
                        @csrf
                        <button type="submit" class="btn-primary">Enable 2FA</button>
                    </form>
                @endif
            @else
                <div style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-check-circle" style="font-size: 1.25rem;"></i>
                    Two-Factor Authentication is currently enabled.
                </div>
                <button onclick="document.getElementById('disable2faModal').classList.add('active')" class="btn-primary" style="background: var(--danger);">Disable 2FA</button>
            @endif
        </div>

        <!-- Data Export -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.25rem;">Data Export</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">Download a complete JSON backup of all your financial data including transactions, goals, debts, and budgets.</p>
                </div>
                <div>
                    <a href="{{ route('profile.export') }}" class="btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; background: var(--accent-primary);">
                        <i class="ph ph-file-arrow-down"></i> Download Backup
                    </a>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="glass-card" style="border: 1px solid rgba(239, 68, 68, 0.3);">
            <h3 style="color: var(--danger); margin-bottom: 0.5rem; font-size: 1.25rem;">Danger Zone</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem;">Permanently delete your account and all associated financial data. This action cannot be undone.</p>
            
            <button onclick="document.getElementById('deleteAccountModal').classList.add('active')" class="btn-primary" style="background: var(--danger);">Delete Account</button>
        </div>

    </div>

    <!-- Delete Account Modal -->
    <div id="deleteAccountModal" class="modal-overlay">
        <div class="modal-content" style="border: 1px solid var(--danger);">
            <div class="modal-header">
                <h3 style="color: var(--danger);">Confirm Account Deletion</h3>
                <button class="close-btn" onclick="document.getElementById('deleteAccountModal').classList.remove('active')">&times;</button>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                Are you absolutely sure you want to delete your account? All your data will be permanently wiped from our servers. Please enter your password to confirm.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="Enter your password">
                </div>
                <button type="submit" class="btn-primary" style="background: var(--danger); width: 100%;">Permanently Delete Account</button>
            </form>
        </div>
    <!-- Disable 2FA Modal -->
    <div id="disable2faModal" class="modal-overlay">
        <div class="modal-content" style="border: 1px solid var(--danger);">
            <div class="modal-header">
                <h3 style="color: var(--danger);">Disable Two-Factor Authentication</h3>
                <button class="close-btn" onclick="document.getElementById('disable2faModal').classList.remove('active')">&times;</button>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                Are you sure you want to disable two-factor authentication? Your account will be less secure. Please enter your password to confirm.
            </p>
            <form method="POST" action="{{ route('profile.2fa.disable') }}">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="Enter your password">
                </div>
                <button type="submit" class="btn-primary" style="background: var(--danger); width: 100%;">Disable 2FA</button>
            </form>
        </div>
    </div>
@endsection
