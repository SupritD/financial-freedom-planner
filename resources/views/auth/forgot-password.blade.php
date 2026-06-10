<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Financial Freedom Planner</title>
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
            max-width: 400px;
            padding: 3rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: inherit;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-input:focus {
            border-color: var(--accent-primary);
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
        }
        .btn-primary:hover {
            background: var(--accent-hover);
        }
        .error-msg {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .success-msg {
            color: var(--success);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
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
            <div style="text-align: center; margin-bottom: 2rem;">
                <i class="ph ph-key" style="font-size: 3rem; color: var(--accent-primary); margin-bottom: 1rem;"></i>
                <h2>Reset Password</h2>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">Enter your email to receive a reset link</p>
            </div>

            @if (session('status'))
                <div class="success-msg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">Send Reset Link</button>
            </form>

            <div style="margin-top: 1.5rem; text-align: center; font-size: 0.875rem; color: var(--text-secondary);">
                Remember your password? <a href="{{ route('login') }}" style="color: var(--accent-primary); text-decoration: none; font-weight: 500;">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>
