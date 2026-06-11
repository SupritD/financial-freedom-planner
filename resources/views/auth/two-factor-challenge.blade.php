@extends('layouts.guest')

@section('title', 'Two-Factor Authentication | Financial Freedom Planner')

@section('content')
    <div style="max-width: 400px; width: 100%;">
        <div class="glass-card" style="padding: 2.5rem; text-align: center;">
            
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 50%; background: rgba(16, 185, 129, 0.1); color: var(--success); margin-bottom: 1.5rem;">
                <i class="ph ph-shield-check" style="font-size: 32px;"></i>
            </div>
            
            <h2 style="color: var(--text-primary); margin-bottom: 0.5rem;">Two-Factor Authentication</h2>
            <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.95rem;">
                Please enter the 6-digit authentication code from your authenticator app to continue.
            </p>

            @if($errors->any())
                <div style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 0.75rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.2fa.verify') }}">
                @csrf
                <div class="form-group" style="text-align: left;">
                    <label class="form-label" style="display: block; text-align: center;">Authentication Code</label>
                    <input type="text" name="one_time_password" class="form-input" required autofocus autocomplete="one-time-code" placeholder="XXXXXX" style="text-align: center; letter-spacing: 0.5rem; font-size: 1.25rem; font-family: monospace;">
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">
                    Verify & Login
                </button>
            </form>

            <div style="margin-top: 2rem;">
                <a href="{{ route('login') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; transition: color 0.3s;" onmouseover="this.style.color='var(--accent-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                    Cancel & Return to Login
                </a>
            </div>
        </div>
    </div>
@endsection
