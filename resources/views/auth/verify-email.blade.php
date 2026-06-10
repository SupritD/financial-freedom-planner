<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | Financial Freedom Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100vw;
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent 40%), var(--bg-primary);
        }
        .auth-card {
            width: 100%;
            max-width: 450px;
            padding: 3rem;
        }
        .btn-primary {
            width: 100%;
            padding: 0.875rem;
            background: var(--accent-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1.5rem;
        }
        .btn-primary:hover {
            background: var(--accent-hover);
        }
        .btn-secondary {
            width: 100%;
            padding: 0.875rem;
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        .btn-secondary:hover {
            color: var(--text-primary);
            border-color: var(--text-primary);
        }
        .success-msg {
            color: var(--success);
            font-size: 0.875rem;
            margin-top: 1rem;
            text-align: center;
            background: rgba(16, 185, 129, 0.1);
            padding: 0.75rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="glass-card auth-card">
            <div style="text-align: center;">
                <i class="ph ph-envelope-open" style="font-size: 3.5rem; color: var(--accent-primary); margin-bottom: 1rem;"></i>
                <h2>Verify Your Email Address</h2>
                <p style="color: var(--text-secondary); margin-top: 1rem; line-height: 1.6;">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="success-msg">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-secondary">Log Out</button>
            </form>
        </div>
    </div>
</body>
</html>
