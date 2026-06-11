<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Freedom Planner | Take Control</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-brand-bg-dark text-brand-text-primary font-sans overflow-x-hidden relative">

    <!-- Ambient background glows -->
    <div class="fixed top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-brand-accent-primary/20 blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-brand-success/10 blur-[120px] pointer-events-none z-0"></div>

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 flex justify-between items-center px-6 lg:px-16 py-4 bg-[#0f111a]/80 backdrop-blur-md border-b border-white/5">
        <a href="/" class="text-2xl font-bold text-brand-text-primary flex items-center gap-2">
            <i class="ph ph-infinity text-brand-accent-primary"></i>
            FF<span class="text-brand-accent-primary">Planner</span>
        </a>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-brand-accent-primary text-white font-semibold rounded-full hover:bg-brand-accent-primary/90 hover:-translate-y-0.5 transition-all">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-6 py-2.5 text-brand-text-primary font-medium border border-brand-border rounded-full hover:bg-white/10 transition-colors hidden sm:block">Sign In</a>
                <a href="{{ route('login') }}" class="px-6 py-2.5 bg-brand-accent-primary text-white font-semibold rounded-full hover:bg-brand-accent-primary/90 hover:-translate-y-0.5 transition-all">Get Started Free</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative z-10 flex flex-col justify-center items-center text-center min-h-[80vh] px-6 py-16 max-w-5xl mx-auto">
        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6 bg-gradient-to-r from-slate-100 to-slate-400 bg-clip-text text-transparent">
            Master Your Money.<br>Design Your Freedom.
        </h1>
        <p class="text-lg md:text-xl text-brand-text-secondary mb-10 max-w-2xl leading-relaxed">
            A rigorous, double-entry financial ledger hidden behind a beautiful, lightning-fast interface. Track every penny, crush your debt, and crush your goals.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-6">
            @auth
                <a href="{{ route('dashboard') }}" class="px-8 py-4 text-lg bg-brand-accent-primary text-white font-semibold rounded-full hover:bg-brand-accent-primary/90 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    Enter Dashboard <i class="ph ph-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-4 text-lg bg-brand-accent-primary text-white font-semibold rounded-full hover:bg-brand-accent-primary/90 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    Start Your Journey <i class="ph ph-rocket"></i>
                </a>
            @endauth
        </div>
    </header>

    <!-- Features Section -->
    <section class="relative z-10 px-6 py-24 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-brand-surface/40 border border-white/5 p-10 rounded-3xl hover:-translate-y-2 hover:bg-brand-surface/60 transition-all duration-300">
            <i class="ph ph-scales text-5xl text-brand-accent-primary mb-6"></i>
            <h3 class="text-2xl font-bold text-brand-text-primary mb-4">Double-Entry Ledger</h3>
            <p class="text-brand-text-secondary leading-relaxed">Powered by BCMath precision. Every income and expense is mathematically verified against your assets and liabilities ensuring zero dropped decimals.</p>
        </div>
        
        <div class="bg-brand-surface/40 border border-white/5 p-10 rounded-3xl hover:-translate-y-2 hover:bg-brand-surface/60 transition-all duration-300">
            <i class="ph ph-target text-5xl text-brand-success mb-6"></i>
            <h3 class="text-2xl font-bold text-brand-text-primary mb-4">Visual Goal Tracking</h3>
            <p class="text-brand-text-secondary leading-relaxed">Stop guessing. Visually map out your emergency funds, vacations, and investments with real-time percentage tracking.</p>
        </div>

        <div class="bg-brand-surface/40 border border-white/5 p-10 rounded-3xl hover:-translate-y-2 hover:bg-brand-surface/60 transition-all duration-300">
            <i class="ph ph-chart-line-up text-5xl text-purple-500 mb-6"></i>
            <h3 class="text-2xl font-bold text-brand-text-primary mb-4">Automated Analytics</h3>
            <p class="text-brand-text-secondary leading-relaxed">Instantly visualize your monthly cashflow through beautiful, interactive charts. Track your trajectory directly on your dashboard.</p>
        </div>
    </section>

</body>
</html>
