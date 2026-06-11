@extends('layouts.guest')

@section('title', 'Two-Factor Authentication | Financial Freedom Planner')

@section('content')
    <div class="glass-card w-full max-w-md p-8 sm:p-10 text-center">
        
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-brand-success/10 text-brand-success mb-6">
            <i class="ph ph-shield-check text-4xl"></i>
        </div>
        
        <h2 class="text-2xl font-semibold mb-2 text-brand-text-primary">Two-Factor Authentication</h2>
        <p class="text-brand-text-secondary mb-8 text-sm sm:text-base">
            Please enter the 6-digit authentication code from your authenticator app to continue.
        </p>

        @if($errors->any())
            <div class="bg-brand-danger/20 text-brand-danger p-3 rounded-lg mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.2fa.verify') }}" class="space-y-4">
            @csrf
            <div>
                <label class="form-label text-center">Authentication Code</label>
                <input type="text" name="one_time_password" class="form-input text-center tracking-[0.5em] text-xl font-mono" required autofocus autocomplete="one-time-code" placeholder="XXXXXX">
            </div>

            <button type="submit" class="btn-primary w-full py-3 text-lg mt-4">
                Verify & Login
            </button>
        </form>

        <div class="mt-8">
            <a href="{{ route('login') }}" class="text-brand-text-secondary hover:text-brand-accent-primary transition-colors text-sm font-medium">
                Cancel & Return to Login
            </a>
        </div>
    </div>
@endsection
