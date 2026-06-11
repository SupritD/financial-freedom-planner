<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Financial Freedom Planner')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Phosphor Icons & Chart.js -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-brand-bg-dark text-brand-text-primary font-sans flex min-h-screen overflow-x-hidden">

    <!-- Mobile Header -->
    <div
        class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-brand-surface border-b border-brand-border flex items-center justify-between px-4 z-40 shadow-md">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-brand-text-primary flex items-center gap-2">
            <i class="ph ph-infinity text-brand-accent-primary"></i>
            FF<span class="text-brand-accent-primary">Planner</span>
        </a>
        <button id="mobile-menu-btn" class="text-brand-text-secondary text-2xl focus:outline-none">
            <i class="ph ph-list"></i>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay"
        class="fixed inset-0 bg-black/60 z-[45] hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-[50] w-64 bg-brand-surface border-r border-brand-border p-6 flex flex-col gap-8 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:flex-shrink-0 shadow-2xl lg:shadow-none">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}"
                class="text-2xl font-bold text-brand-text-primary flex items-center gap-2">
                <i class="ph ph-infinity text-brand-accent-primary"></i>
                FF<span class="text-brand-accent-primary">Planner</span>
            </a>
            <button id="close-sidebar-btn" class="lg:hidden text-brand-text-secondary text-2xl">
                <i class="ph ph-x"></i>
            </button>
        </div>

        <nav class="flex flex-col gap-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-squares-four text-xl"></i> Dashboard
            </a>
            <a href="{{ route('transactions') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('transactions') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-arrows-left-right text-xl"></i> Transactions
            </a>
            <a href="{{ route('budget') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('budget') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-wallet text-xl"></i> Budget
            </a>
            <a href="{{ route('savings') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('savings') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-piggy-bank text-xl"></i> Savings
            </a>
            <a href="{{ route('investments') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('investments') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-trend-up text-xl"></i> Investments
            </a>
            <a href="{{ route('emergency') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('emergency') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-shield-check text-xl"></i> Emergency Fund
            </a>
            <a href="{{ route('goals') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('goals') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-target text-xl"></i> Goals
            </a>
            <a href="{{ route('reports') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('reports') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-chart-pie-slice text-xl"></i> Reports
            </a>
            <a href="{{ route('debt') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('debt') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-bank text-xl"></i> Debt
            </a>

            @if(auth()->user() && auth()->user()->is_admin)
                <div class="my-4 border-t border-brand-border"></div>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-bg-dark/50 text-purple-500' : 'text-purple-400 hover:bg-brand-bg-dark/50 hover:text-purple-500' }}">
                    <i class="ph ph-shield-star text-xl"></i> Admin Panel
                </a>
            @endif

            <div class="my-4 border-t border-brand-border"></div>

            <a href="{{ route('profile') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('profile') ? 'bg-brand-bg-dark/50 text-brand-accent-primary' : 'text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-accent-primary' }}">
                <i class="ph ph-user text-xl"></i> Profile
            </a>

            <form method="POST" action="{{ route('logout') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium text-brand-text-secondary hover:bg-brand-bg-dark/50 hover:text-brand-danger transition-colors cursor-pointer"
                onclick="this.submit();">
                @csrf
                <i class="ph ph-sign-out text-xl"></i> Logout
            </form>
        </nav>
    </aside>

    <main class="flex-1 p-6 lg:p-12 overflow-y-auto mt-16 lg:mt-0 relative z-0 w-full max-w-full">
        <header class="mb-8">
            <h2 class="text-2xl font-semibold">@yield('header')</h2>
        </header>

        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }

            if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
            if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
</body>

</html>