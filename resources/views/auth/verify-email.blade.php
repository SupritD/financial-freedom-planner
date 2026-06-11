<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | Financial Freedom Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-brand-bg-dark text-brand-text-primary font-sans relative overflow-x-hidden min-h-screen flex items-center justify-center">

    <!-- Ambient background glows -->
    <div class="absolute top-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-brand-accent-primary/20 blur-[120px] pointer-events-none z-0"></div>

    <div class="relative z-10 w-full max-w-md p-6">
        <div class="bg-brand-surface/80 backdrop-blur-md border border-brand-border rounded-2xl shadow-2xl p-10">
            <div class="text-center mb-8">
                <i class="ph ph-envelope-open text-6xl text-brand-accent-primary mb-4 inline-block"></i>
                <h2 class="text-2xl font-bold text-brand-text-primary mb-2">Verify Your Email Address</h2>
                <p class="text-brand-text-secondary leading-relaxed">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-brand-success/10 text-brand-success text-sm p-4 rounded-xl text-center mb-6 border border-brand-success/20">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-brand-accent-primary text-white font-semibold rounded-xl hover:bg-brand-accent-primary/90 transition-colors">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3.5 border border-brand-border text-brand-text-secondary font-semibold rounded-xl hover:text-brand-text-primary hover:border-brand-text-primary transition-colors">Log Out</button>
            </form>
        </div>
    </div>
</body>
</html>
