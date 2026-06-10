<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Freedom Planner | Take Control</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.15), transparent 40%),
                        radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.1), transparent 40%),
                        var(--bg-primary);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 4rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .nav-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-outline {
            padding: 0.6rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            border: 1px solid var(--border);
            border-radius: 99px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.1);
        }

        .btn-solid {
            padding: 0.6rem 1.5rem;
            background: var(--accent-primary);
            color: white;
            text-decoration: none;
            border-radius: 99px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-solid:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 4rem 2rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #f8fafc, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            max-width: 600px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
        }

        .btn-large {
            padding: 1rem 2.5rem;
            font-size: 1.125rem;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        /* Features Section */
        .features {
            padding: 6rem 4rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255,255,255,0.05);
            padding: 2.5rem;
            border-radius: 24px;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(30, 41, 59, 0.6);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-primary);
            margin-bottom: 1.5rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .feature-desc {
            color: var(--text-secondary);
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="/" class="brand" style="margin: 0;">
            <i class="ph ph-infinity"></i>
            FF<span>Planner</span>
        </a>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-solid">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-outline">Sign In</a>
                <a href="{{ route('login') }}" class="btn-solid">Get Started Free</a>
            @endauth
        </div>
    </nav>

    <header class="hero">
        <h1>Master Your Money.<br>Design Your Freedom.</h1>
        <p>A rigorous, double-entry financial ledger hidden behind a beautiful, lightning-fast interface. Track every penny, crush your debt, and crush your goals.</p>
        
        <div class="hero-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-solid btn-large">
                    Enter Dashboard <i class="ph ph-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-solid btn-large">
                    Start Your Journey <i class="ph ph-rocket"></i>
                </a>
            @endauth
        </div>
    </header>

    <section class="features">
        <div class="feature-card">
            <i class="ph ph-scales feature-icon"></i>
            <h3 class="feature-title">Double-Entry Ledger</h3>
            <p class="feature-desc">Powered by BCMath precision. Every income and expense is mathematically verified against your assets and liabilities ensuring zero dropped decimals.</p>
        </div>
        
        <div class="feature-card">
            <i class="ph ph-target feature-icon" style="color: var(--success);"></i>
            <h3 class="feature-title">Visual Goal Tracking</h3>
            <p class="feature-desc">Stop guessing. Visually map out your emergency funds, vacations, and investments with real-time percentage tracking.</p>
        </div>

        <div class="feature-card">
            <i class="ph ph-chart-line-up feature-icon" style="color: #a855f7;"></i>
            <h3 class="feature-title">Automated Analytics</h3>
            <p class="feature-desc">Instantly visualize your monthly cashflow through beautiful, interactive charts. Track your trajectory directly on your dashboard.</p>
        </div>
    </section>

</body>
</html>
