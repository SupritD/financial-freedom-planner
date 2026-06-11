<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Financial Freedom Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-brand-bg-dark text-brand-text-primary font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4 py-12" style="background: radial-gradient(circle at top right, rgba(139, 92, 246, 0.1), transparent 40%);">
        <div class="glass-card w-full max-w-md p-8 sm:p-10">
            <div class="text-center mb-8">
                <i class="ph ph-lock-key text-5xl text-brand-accent-primary mb-4 block"></i>
                <h2 class="text-2xl font-semibold">Reset Password</h2>
                <p class="text-brand-text-secondary mt-2 text-sm">Enter your email and we'll send you a link to reset your password.</p>
            </div>

            @if (session('status'))
                <div class="bg-brand-success/20 text-brand-success p-4 rounded-lg mb-6 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="text-brand-danger text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-lg mt-4">Send Reset Link</button>
            </form>
            
            <div class="mt-8 text-center text-sm text-brand-text-secondary">
                Remember your password? <a href="{{ route('login') }}" class="text-brand-accent-primary font-medium hover:text-brand-accent-primary/80 transition-colors">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>
