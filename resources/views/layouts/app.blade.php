<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Financial Freedom Planner')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Phosphor Icons & Chart.js -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">
            <i class="ph ph-infinity"></i>
            FF<span>Planner</span>
        </a>

        <nav class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a href="{{ route('transactions') }}" class="nav-link {{ request()->routeIs('transactions') ? 'active' : '' }}">
                <i class="ph ph-arrows-left-right"></i> Transactions
            </a>
            <a href="{{ route('budget') }}" class="nav-link {{ request()->routeIs('budget') ? 'active' : '' }}">
                <i class="ph ph-wallet"></i> Budget
            </a>
            <a href="{{ route('savings') }}" class="nav-link {{ request()->routeIs('savings') ? 'active' : '' }}">
                <i class="ph ph-piggy-bank"></i> Savings
            </a>
            <a href="{{ route('investments') }}" class="nav-link {{ request()->routeIs('investments') ? 'active' : '' }}">
                <i class="ph ph-trend-up"></i> Investments
            </a>
            <a href="{{ route('emergency') }}" class="nav-link {{ request()->routeIs('emergency') ? 'active' : '' }}">
                <i class="ph ph-shield-check"></i> Emergency Fund
            </a>
            <a href="{{ route('goals') }}" class="nav-link {{ request()->routeIs('goals') ? 'active' : '' }}">
                <i class="ph ph-target"></i> Goals
            </a>
            <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                <i class="ph ph-chart-pie-slice"></i> Reports
            </a>
            <a href="{{ route('debt') }}" class="nav-link {{ request()->routeIs('debt') ? 'active' : '' }}">
                <i class="ph ph-bank"></i> Debt
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <h2>@yield('header')</h2>
            
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('profile') }}" class="user-profile" style="text-decoration: none; color: inherit;">
                    <i class="ph ph-user"></i>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="user-profile" style="cursor: pointer;" onclick="this.submit();">
                    @csrf
                    <i class="ph ph-sign-out"></i>
                    <span>Logout</span>
                </form>
            </div>
        </header>

        @yield('content')
    </main>

</body>
</html>
