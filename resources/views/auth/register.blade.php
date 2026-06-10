<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Financial Freedom Planner</title>
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
            margin: 2rem 0;
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
        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .auth-links a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="glass-card auth-card">
            <div style="text-align: center; margin-bottom: 2rem;">
                <i class="ph ph-user-plus" style="font-size: 3rem; color: var(--accent-primary); margin-bottom: 1rem;"></i>
                <h2>Create Account</h2>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">Join the Financial Freedom Planner</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number (Optional)</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn-primary">Sign Up</button>
            </form>

            <div class="auth-links">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>
