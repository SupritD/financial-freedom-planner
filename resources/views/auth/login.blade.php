<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Financial Freedom Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-brand-bg-dark text-brand-text-primary font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4" style="background: radial-gradient(circle at top right, rgba(139, 92, 246, 0.1), transparent 40%);">
        <div class="glass-card w-full max-w-md p-8 sm:p-10">
            <div class="text-center mb-8">
                <i class="ph ph-infinity text-5xl text-brand-accent-primary mb-4 block"></i>
                <h2 class="text-2xl font-semibold">Welcome Back</h2>
                <p class="text-brand-text-secondary mt-2">Sign in to your financial planner</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', 'demo@example.com') }}" required autofocus>
                    @error('email')
                        <div class="text-brand-danger text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" value="password" required>
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-lg mt-2">Sign In</button>
            </form>
            
            <div class="mt-8 text-center text-sm text-brand-text-secondary">
                Don't have an account? <a href="{{ route('register') }}" class="text-brand-accent-primary font-medium hover:text-brand-accent-primary/80 transition-colors">Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>
