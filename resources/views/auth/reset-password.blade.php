<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | Financial Freedom Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-brand-bg-dark text-brand-text-primary font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4 py-12" style="background: radial-gradient(circle at top right, rgba(139, 92, 246, 0.1), transparent 40%);">
        <div class="glass-card w-full max-w-md p-8 sm:p-10">
            <div class="text-center mb-8">
                <i class="ph ph-password text-5xl text-brand-accent-primary mb-4 block"></i>
                <h2 class="text-2xl font-semibold">Set New Password</h2>
                <p class="text-brand-text-secondary mt-2 text-sm">Please enter your new password below.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', request()->email) }}" required autofocus readonly>
                    @error('email')
                        <div class="text-brand-danger text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password')
                        <div class="text-brand-danger text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-lg mt-4">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
