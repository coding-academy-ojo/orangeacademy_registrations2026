@extends('layouts.base')
@section('title', 'Admin Portal — Orange Academy')
@section('body')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #06060d;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated background */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,121,0,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,121,0,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: grid-move 20s linear infinite;
        }
        @keyframes grid-move {
            0%   { background-position: 0 0; }
            100% { background-position: 60px 60px; }
        }

        .bg-glow-1 {
            position: fixed;
            top: -200px; left: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(255,121,0,0.08) 0%, transparent 70%);
            animation: float1 8s ease-in-out infinite;
        }
        .bg-glow-2 {
            position: fixed;
            bottom: -200px; right: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,70,0,0.06) 0%, transparent 70%);
            animation: float2 10s ease-in-out infinite;
        }
        @keyframes float1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(40px,30px)} }
        @keyframes float2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-30px,-40px)} }

        /* Card */
        .card {
            position: relative;
            z-index: 10;
            background: rgba(13,13,22,0.95);
            border: 1px solid rgba(255,121,0,0.15);
            border-radius: 24px;
            padding: 2.5rem 2.25rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.6), 0 0 60px rgba(255,121,0,0.05);
            animation: card-in 0.5s cubic-bezier(.22,.68,0,1.2) both;
            margin: 1rem;
        }
        @keyframes card-in {
            from { opacity:0; transform:translateY(30px) scale(0.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* Header */
        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .shield-wrap {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, rgba(255,107,53,0.15), rgba(247,147,30,0.1));
            border: 1px solid rgba(255,121,0,0.25);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            color: #ff7900;
            position: relative;
        }
        .shield-wrap::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 20px;
            border: 1px solid rgba(255,121,0,0.15);
            animation: pulse-ring 2.5s ease infinite;
        }
        @keyframes pulse-ring {
            0%,100% { opacity:.6; transform:scale(1); }
            50%      { opacity:0; transform:scale(1.15); }
        }

        .portal-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
            margin-bottom: 0.3rem;
        }
        .portal-sub {
            color: rgba(255,255,255,0.35);
            font-size: 0.875rem;
        }

        /* Security badge */
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,121,0,0.08);
            border: 1px solid rgba(255,121,0,0.2);
            border-radius: 999px;
            padding: 5px 14px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(255,180,80,0.9);
            margin-top: 0.75rem;
        }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #ff7900;
            animation: blink 2s ease infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.07), transparent);
            margin: 1.5rem 0;
        }

        /* Error */
        .alert-error {
            background: rgba(220,53,69,0.1);
            border: 1px solid rgba(220,53,69,0.3);
            color: #ff8080;
            border-radius: 10px;
            padding: 11px 15px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form fields */
        .field { margin-bottom: 1.1rem; }
        .field-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.45);
            margin-bottom: 7px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.25);
            font-size: 1rem;
            pointer-events: none;
            transition: color .2s;
        }
        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 12px;
            padding: 13px 13px 13px 40px;
            color: #fff;
            font-size: 0.93rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.2); }
        .field-input:focus {
            border-color: rgba(255,121,0,0.55);
            background: rgba(255,121,0,0.04);
            box-shadow: 0 0 0 3px rgba(255,121,0,0.1);
        }
        .field-input:focus ~ .field-icon { color: #ff7900; }
        .field-wrap:focus-within .field-icon { color: #ff7900; }
        .field-input.is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback { color: #ff6b6b; font-size: 0.78rem; margin-top: 5px; display: block; }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: rgba(255,255,255,0.25);
            cursor: pointer; font-size: 1rem;
            transition: color .2s;
            padding: 0;
        }
        .pw-toggle:hover { color: rgba(255,255,255,0.6); }

        /* Remember row */
        .remember-row {
            display: flex; align-items: center;
            margin-bottom: 1.4rem;
        }
        .check-label {
            display: flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.4);
            font-size: 0.85rem;
            cursor: pointer;
        }
        .check-label input[type="checkbox"] {
            accent-color: #ff7900;
            width: 15px; height: 15px;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 0.97rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            letter-spacing: 0.02em;
            box-shadow: 0 8px 24px rgba(255,107,53,0.3);
            transition: all .3s;
            display: flex; align-items: center;
            justify-content: center; gap: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(255,107,53,0.45);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Back link */
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: rgba(255,255,255,0.3);
            font-size: 0.82rem;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            transition: color .2s;
        }
        .back-link a:hover { color: rgba(255,255,255,0.6); }

        /* Logo */
        .logo-row {
            display: flex; align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        .logo-row img {
            height: 38px;
            object-fit: contain;
        }
    </style>

    <div class="bg-grid"></div>
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="card">
        <div class="card-header">
            <div class="logo-row">
                <img src="{{ asset('images/logo.png') }}" alt="Orange Academy">
            </div>

            <div class="shield-wrap">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <h1 class="portal-title">Admin Portal</h1>
            <p class="portal-sub">Restricted access — authorized personnel only</p>
            <div class="security-badge">
                <span class="badge-dot"></span>
                Secure Login
            </div>
        </div>

        <div class="divider"></div>

        @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert-error" style="background:rgba(40,167,69,.1);border-color:rgba(40,167,69,.3);color:#6fcf97;">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" autocomplete="on">
            @csrf

            <div class="field">
                <label class="field-label" for="email">Email Address</label>
                <div class="field-wrap">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        placeholder="admin@example.com"
                        class="field-input @error('email') is-invalid @enderror"
                        required>
                    <i class="bi bi-envelope field-icon"></i>
                </div>
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label class="field-label" for="password">Password</label>
                <div class="field-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="field-input @error('password') is-invalid @enderror"
                        required>
                    <i class="bi bi-lock field-icon"></i>
                    <button type="button" class="pw-toggle" onclick="togglePw()" id="pwToggle">
                        <i class="bi bi-eye" id="pwIcon"></i>
                    </button>
                </div>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="remember-row">
                <label class="check-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Keep me signed in
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-shield-check"></i>
                Sign In to Admin Panel
            </button>
        </form>

        <div class="back-link">
            <a href="/"><i class="bi bi-arrow-left"></i> Back to website</a>
        </div>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
@endsection
