<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Financial Freedom Planner')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-brand-bg-dark text-brand-text-primary font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4 py-12" style="background: radial-gradient(circle at top right, rgba(139, 92, 246, 0.1), transparent 40%);">
        @yield('content')
    </div>
</body>
</html>
