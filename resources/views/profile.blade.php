@extends('layouts.app')

@section('title', 'Profile Settings | Financial Freedom Planner')
@section('header', 'Profile Settings')

@section('content')
    <div class="max-w-4xl mx-auto w-full">

        @if(session('success'))
            <div class="bg-brand-success/20 text-brand-success p-4 rounded-xl mb-6 flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-brand-success hover:text-white transition-colors text-xl font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-brand-danger/20 text-brand-danger p-4 rounded-xl mb-6">
                <ul class="list-disc pl-6 m-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Information & Security -->
        <div class="glass-card mb-8">
            <h3 class="text-brand-text-primary text-xl font-semibold mb-6">Account Information & Security</h3>
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    </div>
                </div>

                <div class="my-8 border-t border-brand-border"></div>

                <h4 class="text-brand-text-primary text-lg font-medium mb-4">Update Password</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" placeholder="Leave blank to keep current">
                    </div>
                    <div>
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-input">
                    </div>
                </div>

                <div class="text-right mt-6">
                    <button type="submit" class="btn-primary px-8 py-3 w-full sm:w-auto">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="glass-card mb-8">
            <h3 class="text-brand-text-primary text-xl font-semibold mb-2">Two-Factor Authentication</h3>
            <p class="text-brand-text-secondary text-sm mb-6">Add additional security to your account using two-factor authentication.</p>

            @if(empty($user->two_factor_secret))
                @if($qrCode)
                    <div class="bg-brand-surface p-6 rounded-xl text-center mb-6">
                        <p class="text-brand-text-primary mb-4">Scan this QR code with your authenticator application (e.g., Google Authenticator, Authy).</p>
                        <div class="bg-white p-4 inline-block rounded-lg shadow-lg">
                            {!! $qrCode !!}
                        </div>
                        <p class="text-brand-text-secondary text-sm mt-4 font-mono bg-black/20 inline-block px-3 py-1 rounded">Setup Key: {{ $secret }}</p>
                    </div>

                    <form method="POST" action="{{ route('profile.2fa.confirm') }}" class="max-w-sm mx-auto sm:mx-0">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Verify Code</label>
                            <input type="text" name="code" class="form-input text-center tracking-widest text-lg font-mono" placeholder="XXXXXX" required>
                        </div>
                        <button type="submit" class="btn-primary w-full py-3">Confirm & Enable 2FA</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('profile.2fa.enable') }}">
                        @csrf
                        <button type="submit" class="btn-primary px-6 py-3 w-full sm:w-auto">Enable 2FA</button>
                    </form>
                @endif
            @else
                <div class="bg-brand-success/10 text-brand-success p-4 rounded-xl mb-6 flex items-center gap-3 border border-brand-success/20">
                    <i class="ph ph-check-circle text-2xl"></i>
                    <span class="font-medium">Two-Factor Authentication is currently enabled.</span>
                </div>
                <button onclick="document.getElementById('disable2faModal').classList.remove('hidden')" class="btn-primary !bg-brand-danger hover:!bg-brand-danger/90 shadow-lg shadow-brand-danger/20 px-6 py-3 w-full sm:w-auto">Disable 2FA</button>
            @endif
        </div>

        <!-- Data Export -->
        <div class="glass-card mb-8">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-6">
                <div>
                    <h3 class="text-brand-text-primary text-xl font-semibold mb-2">Data Export</h3>
                    <p class="text-brand-text-secondary text-sm">Download a complete JSON backup of all your financial data including transactions, goals, debts, and budgets.</p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('profile.export') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 w-full sm:w-auto hover:no-underline">
                        <i class="ph ph-file-arrow-down text-xl"></i> Download Backup
                    </a>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="glass-card border border-brand-danger/30 relative overflow-hidden">
            <div class="absolute inset-0 bg-brand-danger/5 pointer-events-none"></div>
            <div class="relative z-10">
                <h3 class="text-brand-danger text-xl font-semibold mb-2">Danger Zone</h3>
                <p class="text-brand-text-secondary text-sm mb-6">Permanently delete your account and all associated financial data. This action cannot be undone.</p>
                
                <button onclick="document.getElementById('deleteAccountModal').classList.remove('hidden')" class="btn-primary !bg-brand-danger hover:!bg-brand-danger/90 shadow-lg shadow-brand-danger/20 px-6 py-3 w-full sm:w-auto">Delete Account</button>
            </div>
        </div>

    </div>

    <!-- Delete Account Modal -->
    <div id="deleteAccountModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-danger/50 rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-brand-danger/5 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-brand-danger">Confirm Account Deletion</h3>
                    <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('deleteAccountModal').classList.add('hidden')">&times;</button>
                </div>
                <p class="text-brand-text-secondary text-sm mb-6">
                    Are you absolutely sure you want to delete your account? All your data will be permanently wiped from our servers. Please enter your password to confirm.
                </p>
                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-6">
                    @csrf
                    @method('DELETE')
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn-primary w-full py-3 !bg-brand-danger hover:!bg-brand-danger/90 shadow-lg shadow-brand-danger/20">Permanently Delete Account</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Disable 2FA Modal -->
    <div id="disable2faModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4 hidden flex">
        <div class="bg-brand-surface border border-brand-danger/50 rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-brand-danger/5 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-brand-danger">Disable 2FA</h3>
                    <button class="text-brand-text-secondary hover:text-brand-text-primary text-2xl transition-colors focus:outline-none" onclick="document.getElementById('disable2faModal').classList.add('hidden')">&times;</button>
                </div>
                <p class="text-brand-text-secondary text-sm mb-6">
                    Are you sure you want to disable two-factor authentication? Your account will be less secure. Please enter your password to confirm.
                </p>
                <form method="POST" action="{{ route('profile.2fa.disable') }}" class="space-y-6">
                    @csrf
                    @method('DELETE')
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn-primary w-full py-3 !bg-brand-danger hover:!bg-brand-danger/90 shadow-lg shadow-brand-danger/20">Disable 2FA</button>
                </form>
            </div>
        </div>
    </div>
@endsection
