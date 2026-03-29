@extends('layouts.base')
@section('title', 'Create Account — Orange Academy')
@section('body')
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800&family=Fira+Code:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --orange-primary: #ff6b35;
            --orange-dark: #e55a2b;
            --orange-light: #ff9d42;
            --code-bg: #0d1117;
            --code-border: rgba(255, 121, 0, 0.2);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body.lang-ar {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
        }

        .auth-page {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT Form Panel ── */
        .auth-form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 2.5rem 2rem;
            background: #0d0d14;
            position: relative;
            overflow: hidden;
            max-width: 55%;
        }

        .auth-form-panel::before {
            content: '';
            position: absolute;
            top: -30%;
            left: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 121, 0, 0.06) 0%, transparent 70%);
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
            padding: 1.5rem;
            margin: 0 auto;
        }

        .form-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-top: 0.5rem;
        }

        .form-heading {
            margin-bottom: 0.5rem;
            padding-top: 1rem;
        }

        .form-subheading {
            margin-bottom: 1.5rem;
        }

        .form-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .form-brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(255, 121, 0, 0.35);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-brand:hover .form-brand-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 24px rgba(255, 121, 0, 0.5);
        }

        .form-brand-icon svg {
            width: 18px;
            color: white;
            transition: transform 0.3s;
        }

        .form-brand:hover .form-brand-icon svg {
            transform: scale(1.2);
        }

        .form-brand-name {
            font-size: 1rem;
            font-weight: 700;
            color: white;
        }

        .lang-toggle {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 4px;
        }

        .lang-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.45);
            padding: 5px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 7px;
            transition: all 0.25s;
        }

        .lang-btn.active,
        .lang-btn:hover {
            background: #ff7900;
            color: white;
        }

        .form-heading {
            font-size: 1.9rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.75rem;
            margin-top: 0.5rem;
        }

        .form-subheading {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
            letter-spacing: -0.02em;
        }

        .form-subheading {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            font-size: 1rem;
            z-index: 3;
        }

        .auth-input {
            width: 100%;
            padding: 14px 16px 14px 42px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
            transition: color 0.2s;
            pointer-events: none;
        }

        body.lang-ar .input-icon {
            left: auto;
            right: 14px;
        }

        .auth-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 14px 14px 14px 42px;
            color: white;
            font-size: 0.95rem;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
        }

        body.lang-ar .auth-input {
            padding: 14px 42px 14px 14px;
        }

        .auth-input::placeholder {
            color: rgba(255, 255, 255, 0.25);
        }

        .auth-input:focus {
            border-color: rgba(255, 121, 0, 0.6);
            background: rgba(255, 121, 0, 0.04);
            box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.12);
        }

        .input-wrap:focus-within .input-icon {
            color: #ff7900;
        }

        .auth-input.is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            color: #ff6b6b;
            font-size: 0.8rem;
            margin-top: 6px;
            display: block;
        }

        /* Phone Input Group */
        .phone-input-group {
            display: flex;
            align-items: stretch;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .phone-input-group:focus-within {
            border-color: rgba(255, 121, 0, 0.6);
            background: rgba(255, 121, 0, 0.04);
            box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.12);
        }
        .phone-prefix {
            display: flex;
            align-items: center;
            padding: 0 12px 0 16px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 700;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.95rem;
        }
        body.lang-ar .phone-prefix {
            padding: 0 16px 0 12px;
            border-right: none;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            direction: ltr; /* keep prefix as +962 */
        }
        .phone-input-group .auth-input {
            border: none;
            background: transparent;
            border-radius: 0 12px 12px 0;
            padding-left: 12px;
        }
        body.lang-ar .phone-input-group .auth-input {
            border-radius: 12px 0 0 12px;
            padding-right: 12px;
            padding-left: 14px;
        }
        .phone-input-group .input-icon {
            display: none;
        }

        /* Password strength indicator */
        .strength-bar {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }

        .strength-seg {
            height: 3px;
            flex: 1;
            border-radius: 3px;
            background: rgba(255, 255, 255, 0.1);
            transition: background 0.3s;
        }

        .strength-seg.weak {
            background: #dc3545;
        }

        .strength-seg.medium {
            background: #ffc107;
        }

        .strength-seg.strong {
            background: #28a745;
        }

        .btn-auth {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.02em;
            box-shadow: 0 8px 24px rgba(255, 107, 53, 0.35);
            transition: all 0.3s;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(255, 107, 53, 0.45);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #ff7900;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            color: #ffb347;
        }

        .floating-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 121, 0, 0.1);
            border: 1px solid rgba(255, 121, 0, 0.2);
            color: #ff9d42;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .floating-badge .dot-live {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff7900;
            animation: blink 2s ease infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff8080;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .terms-note {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.3);
            text-align: center;
            margin-top: 1rem;
            line-height: 1.6;
        }

        .terms-note a {
            color: rgba(255, 121, 0, 0.7);
            text-decoration: none;
        }

        /* ── RIGHT Showcase Panel ── */
        .auth-showcase {
            display: none;
            flex: 0 0 45%;
            background: #0a0a0f;
            position: relative;
            overflow: hidden;
            padding: 3.5rem 3rem;
            flex-direction: column;
            justify-content: center;
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (min-width: 1024px) {
            .auth-showcase {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .auth-form-panel {
                padding: 1.5rem 1rem;
            }
            
            .form-container {
                max-width: 100%;
            }
            
            .code-window {
                font-size: 0.7rem;
                padding: 1rem;
            }
            
            .tech-badges {
                justify-content: center;
            }
            
            .stats-row {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .stat-box {
                min-width: 80px;
            }
            
            .form-heading {
                font-size: 1.5rem;
            }
        }

        .showcase-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 30%, rgba(255, 121, 0, 0.15) 0%, transparent 60%),
                radial-gradient(circle at 20% 80%, rgba(255, 121, 0, 0.07) 0%, transparent 50%);
        }

        .showcase-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* Animated code blocks */
        .code-window {
            background: rgba(13, 17, 23, 0.8);
            border: 1px solid rgba(255, 121, 0, 0.2);
            border-radius: 12px;
            padding: 1.2rem;
            margin: 1.5rem 0;
            font-family: 'Fira Code', monospace;
            font-size: 0.8rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }

        .code-window::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid rgba(255, 121, 0, 0.1);
        }

        .code-window-dots {
            display: flex;
            gap: 6px;
            padding: 10px;
            position: absolute;
            top: 8px;
            left: 10px;
        }

        .code-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .code-dot.red { background: #ff5f56; }
        .code-dot.yellow { background: #ffbd2e; }
        .code-dot.green { background: #27ca3f; }

        .code-content {
            padding-top: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
        }

        .code-keyword { color: #ff7900; }
        .code-function { color: #61afef; }
        .code-string { color: #98c379; }
        .code-comment { color: #5c6370; font-style: italic; }
        .code-class { color: #e5c07b; }

        @keyframes typeCursor {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 16px;
            background: #ff7900;
            margin-left: 2px;
            animation: typeCursor 0.8s step-end infinite;
            vertical-align: middle;
        }

        /* Floating tech badges */
        .tech-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 1.5rem 0;
        }

        .tech-badge {
            background: rgba(255, 121, 0, 0.1);
            border: 1px solid rgba(255, 121, 0, 0.25);
            color: #ff9d42;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }

        .tech-badge:hover {
            background: rgba(255, 121, 0, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 121, 0, 0.3);
        }

        .tech-badge i {
            font-size: 1rem;
        }

        /* Animated stats */
        .stats-row {
            display: flex;
            gap: 1.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .stat-box {
            text-align: center;
        }

        .stat-num {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            display: block;
            background: linear-gradient(135deg, #ff7900, #ffb347);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: statPulse 2s ease-in-out infinite;
        }

        @keyframes statPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        .showcase-content {
            position: relative;
            z-index: 2;
        }

        .showcase-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            color: #ff9d42;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        .showcase-heading {
            font-size: clamp(1.6rem, 2.2vw, 2.2rem);
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .showcase-heading .gradient {
            background: linear-gradient(120deg, #ff7900, #ffb347, #ff4b00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .showcase-desc {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        /* Feature list */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .feature-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 121, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff9d42;
            font-size: 1.1rem;
            flex-shrink: 0;
            border: 1px solid rgba(255, 121, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .feature-item:hover .feature-icon {
            background: rgba(255, 121, 0, 0.2);
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 8px 20px rgba(255, 121, 0, 0.3);
        }

        .feature-text h6 {
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 3px;
        }

        .feature-text p {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.83rem;
            line-height: 1.5;
            margin: 0;
        }

        .feature-item {
            transition: transform 0.3s;
        }

        .feature-item:hover {
            transform: translateX(5px);
        }

        body.lang-ar .feature-item:hover {
            transform: translateX(-5px);
        }

        /* Stats row */
        .stats-row {
            display: flex;
            gap: 1.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .stat-box {
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-box:hover {
            transform: translateY(-5px);
        }

        .stat-num {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            display: block;
            background: linear-gradient(135deg, #ff7900, #ffb347);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: statPulse 2s ease-in-out infinite;
        }

        @keyframes statPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        .stat-lbl {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 500;
        }

        /* Testimonial card */
        .testimonial-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 14px;
            padding: 1.2rem 1.4rem;
            margin-bottom: 2rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 5rem;
            color: rgba(255, 121, 0, 0.1);
            font-family: Georgia, serif;
            line-height: 1;
        }

        .testimonial-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 121, 0, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(255, 121, 0, 0.2);
        }

        .testimonial-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
            line-height: 1.6;
            font-style: italic;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: white;
            transition: transform 0.3s;
        }

        .testimonial-card:hover .avatar {
            transform: scale(1.1);
        }

        .author-info .name {
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .author-info .role {
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.75rem;
        }

        .star-row {
            color: #ff9d42;
            font-size: 0.8rem;
            margin-bottom: 8px;
        }
    </style>

    <div class="auth-page">
        {{-- ── LEFT Form Panel ── --}}
        <div class="auth-form-panel">
            <div class="form-container">
                <div class="form-top-bar">
                    <a href="/" class="form-brand">
                        <div class="form-brand-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round">
                                <polyline points="4 17 10 11 4 5"></polyline>
                                <line x1="12" y1="19" x2="20" y2="19"></line>
                            </svg>
                        </div>
                        <span class="form-brand-name d-none d-md-block" data-en="Orange Academy"
                            data-ar="أكاديمية أورنج">Orange Academy</span>
                    </a>
                    <div class="lang-toggle">
                        <button class="lang-btn active" id="btnEN" onclick="setLang('en')">EN</button>
                        <button class="lang-btn" id="btnAR" onclick="setLang('ar')">AR</button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="floating-badge"><span class="dot-live"></span> <span data-en="Free to Apply"
                        data-ar="التقديم مجاني">Free to Apply</span></div>
                <h1 class="form-heading" data-en="Start your journey" data-ar="ابدأ رحلتك">Start your journey</h1>
                <p class="form-subheading" data-en="Create your account to apply to Orange Academy"
                    data-ar="أنشئ حسابك للتقديم في أكاديمية أورنج">Create your account to apply to Orange Academy</p>

                <form method="POST" action="{{ route('register') }}" autocomplete="on">
                    @csrf

                    <div class="input-group-custom">
                        <label class="input-label" data-en="Email Address" data-ar="البريد الإلكتروني">Email Address</label>
                        <div class="input-wrap">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email"
                                class="auth-input email @error('email') is-invalid @enderror" placeholder="you@example.com"
                                required>
                            <i class="bi bi-envelope input-icon"></i>
                        </div>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="input-group-custom">
                        <label class="input-label" data-en="Phone Number" data-ar="رقم الهاتف">Phone Number</label>
                        <div class="phone-input-group @error('phone') is-invalid @enderror">
                            <span class="phone-prefix">+962</span>
                            <div class="input-wrap w-100">
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" autocomplete="tel"
                                    class="auth-input phone @error('phone') is-invalid @enderror" placeholder="7XXXXXXXX"
                                    required maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)"
                                    style="padding-left: 12px;">
                            </div>
                        </div>
                        @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="input-group-custom">
                        <label class="input-label" data-en="Password" data-ar="كلمة المرور">Password</label>
                        <div class="input-wrap">
                            <input type="password" name="password" id="password" autocomplete="new-password"
                                class="auth-input password @error('password') is-invalid @enderror"
                                placeholder="Min. 8 characters" required>
                            <i class="bi bi-lock input-icon"></i>
                        </div>
                        <div class="strength-bar" id="strengthBar">
                            <div class="strength-seg" id="seg1"></div>
                            <div class="strength-seg" id="seg2"></div>
                            <div class="strength-seg" id="seg3"></div>
                            <div class="strength-seg" id="seg4"></div>
                        </div>
                        <div style="text-align: right; font-size: 1.2rem; margin-top: 4px;" id="strengthEmoji"></div>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="input-group-custom">
                        <label class="input-label" data-en="Confirm Password" data-ar="تأكيد كلمة المرور">Confirm
                            Password</label>
                        <div class="input-wrap">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                autocomplete="new-password" class="auth-input password_confirmation" placeholder="••••••••"
                                required>
                            <i class="bi bi-shield-check input-icon"></i>
                        </div>
                    </div>

                    <div class="input-group-custom d-flex align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="radio" name="terms"
                                id="termsAgree" value="1" required
                                style="accent-color: #ff6b35; width: 1.2rem; height: 1.2rem; margin-top: 0.2rem;">
                            <label class="form-check-label ms-2 text-white-50" for="termsAgree"
                                style="font-size: 0.85rem; cursor: pointer;">
                                <span data-en="I agree to the" data-ar="أوافق على ">I agree to the</span>
                                <a href="{{ route('terms') }}" target="_blank" class="text-orange fw-bold"
                                    style="color: #ff9d42; text-decoration: none;" data-en="terms & conditions"
                                    data-ar="الشروط والأحكام">terms & conditions</a>
                                <span data-en=" Orange. *" data-ar=" أورنج. *"> Orange. *</span>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="bi bi-rocket-takeoff"></i>
                        <span data-en="Create Free Account" data-ar="إنشاء حساب مجاني">Create Free Account</span>
                    </button>

                    <p class="terms-note"
                        data-en="By creating an account you agree to our Terms of Service and Privacy Policy."
                        data-ar="بإنشاء حساب، أنت توافق على شروط الخدمة وسياسة الخصوصية.">By creating an account you agree
                        to our Terms of Service and Privacy Policy.</p>
                </form>

                <p class="form-footer" style="margin-top: 1.25rem;">
                    <span data-en="Already have an account?" data-ar="لديك حساب بالفعل؟">Already have an account?</span>
                    <a href="{{ route('login') }}" data-en=" Sign in" data-ar=" تسجيل الدخول"> Sign in</a>
                </p>
            </div>
        </div>

        {{-- ── RIGHT Showcase Panel ── --}}
        <div class="auth-showcase">
            <div class="showcase-bg"></div>
            <div class="showcase-grid"></div>
            <div class="showcase-content">
                <div class="showcase-eyebrow" data-en="Why Orange Academy?" data-ar="لماذا أكاديمية أورنج؟">Why Orange
                    Academy?</div>
                <h2 class="showcase-heading">
                    <span data-en="Everything you need to" data-ar="كل ما تحتاجه">Everything you need to</span><br>
                    <span class="gradient" data-en="launch your tech career" data-ar="لإطلاق مسيرتك التقنية">launch your
                        tech career</span>
                </h2>
                <p class="showcase-desc"
                    data-en="Join thousands of students who have broken into tech through our hands-on, industry-focused programs."
                    data-ar="انضم إلى آلاف الطلاب الذين انضموا إلى قطاع التكنولوجيا من خلال برامجنا العملية الموجهة للصناعة.">
                    Join thousands of students who have broken into tech through our hands-on, industry-focused programs.
                </p>

                {{-- Code Editor Window --}}
                <div class="code-window">
                    <div class="code-window-dots">
                        <span class="code-dot red"></span>
                        <span class="code-dot yellow"></span>
                        <span class="code-dot green"></span>
                    </div>
                    <div class="code-content">
                        <span class="code-comment">// Start your coding journey</span><br>
                        <span class="code-keyword">class</span> <span class="code-class">Student</span> <span class="code-keyword">extends</span> <span class="code-class">Learner</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">register</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Orange Academy"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br>
                        <span class="code-class">Student</span>::<span class="code-function">apply</span>()<span class="typing-cursor"></span>
                    </div>
                </div>

                {{-- Tech Stack Badges --}}
                <div class="tech-badges">
                    <div class="tech-badge"><i class="bi bi-filetype-php"></i> PHP</div>
                    <div class="tech-badge"><i class="bi bi-hexagon"></i> Laravel</div>
                    <div class="tech-badge"><i class="bi bi-filetype-js"></i> JavaScript</div>
                    <div class="tech-badge"><i class="bi bi-database"></i> MySQL</div>
                    <div class="tech-badge"><i class="bi bi-git"></i> Git</div>
                </div>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-code-slash"></i></div>
                        <div class="feature-text">
                            <h6 data-en="Real-World Projects" data-ar="مشاريع عملية واقعية">Real-World Projects</h6>
                            <p data-en="Build production-ready apps using PHP, Laravel, Node.js and modern frameworks."
                                data-ar="أنشئ تطبيقات جاهزة للإنتاج باستخدام PHP وLaravel وNode.js والأطر الحديثة.">Build
                                production-ready apps using PHP, Laravel, Node.js and modern frameworks.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-person-check"></i></div>
                        <div class="feature-text">
                            <h6 data-en="Expert Instructors" data-ar="مدربون خبراء">Expert Instructors</h6>
                            <p data-en="Learn directly from developers working at top tech companies in Jordan and beyond."
                                data-ar="تعلم مباشرة من مطورين يعملون في أفضل شركات التكنولوجيا في الأردن وخارجه.">Learn
                                directly from developers working at top tech companies in Jordan and beyond.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-briefcase"></i></div>
                        <div class="feature-text">
                            <h6 data-en="Career Placement Support" data-ar="دعم التوظيف">Career Placement Support</h6>
                            <p data-en="Get resume reviews, mock interviews, and direct referrals to hiring partners."
                                data-ar="احصل على مراجعة سيرتك الذاتية وإجراء مقابلات وهمية وإحالات مباشرة لشركاء التوظيف.">
                                Get resume reviews, mock interviews, and direct referrals to hiring partners.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-geo-alt"></i></div>
                        <div class="feature-text">
                            <h6 data-en="Multiple Locations" data-ar="مواقع متعددة">Multiple Locations</h6>
                            <p data-en="Campuses across Jordan: Amman, Irbid, Zarqa, Aqaba and more."
                                data-ar="حرم جامعي في الأردن: عمان، إربد، الزرقاء، العقبة والمزيد.">Campuses across Jordan:
                                Amman, Irbid, Zarqa, Aqaba and more.</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="star-row">★★★★★</div>
                    <p class="testimonial-text"
                        data-en='"Orange Academy completely changed my career trajectory. Within 6 months I landed my first dev job."'
                        data-ar='"غيّرت أكاديمية أورنج مسار مسيرتي المهنية كلياً. في غضون 6 أشهر، حصلت على أول وظيفة لي كمطوّر."'>
                        "Orange Academy completely changed my career trajectory. Within 6 months I landed my first dev job."
                    </p>
                    <div class="testimonial-author">
                        <div class="avatar">A</div>
                        <div class="author-info">
                            <div class="name" data-en="Ahmed Al-Rashid" data-ar="أحمد الراشد">Ahmed Al-Rashid</div>
                            <div class="role" data-en="Junior Laravel Developer, Amman"
                                data-ar="مطوّر Laravel مبتدئ، عمّان">Junior Laravel Developer, Amman</div>
                        </div>
                    </div>
                </div>

                <div class="stats-row">
                    <div class="stat-box"><span class="stat-num">5k+</span><span class="stat-lbl" data-en="Graduates"
                            data-ar="خريج">Graduates</span></div>
                    <div class="stat-box"><span class="stat-num">95%</span><span class="stat-lbl" data-en="Job Placement"
                            data-ar="توظيف">Job Placement</span></div>
                    <div class="stat-box"><span class="stat-num">5</span><span class="stat-lbl" data-en="Cities"
                            data-ar="مدن">Cities</span></div>
                    <div class="stat-box"><span class="stat-num">50+</span><span class="stat-lbl" data-en="Courses"
                            data-ar="دورة">Courses</span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setLang(lang) {
            document.body.classList.toggle('lang-ar', lang === 'ar');
            document.getElementById('btnEN').classList.toggle('active', lang === 'en');
            document.getElementById('btnAR').classList.toggle('active', lang === 'ar');
            localStorage.setItem('lang', lang);
            document.querySelectorAll('[data-en]').forEach(el => {
                el.textContent = el.getAttribute('data-' + lang) || el.getAttribute('data-en');
            });
        }
        const saved = localStorage.getItem('lang') || 'en';
        setLang(saved);

        // Password strength indicator with emoji feedback
        const pw = document.getElementById('password');
        const strengthEmoji = document.getElementById('strengthEmoji');
        if (pw) {
            pw.addEventListener('input', function () {
                const val = this.value;
                const segs = [document.getElementById('seg1'), document.getElementById('seg2'),
                document.getElementById('seg3'), document.getElementById('seg4')];
                segs.forEach(s => { s.className = 'strength-seg'; });
                if (val.length === 0) {
                    if (strengthEmoji) strengthEmoji.textContent = '';
                    return;
                }
                const score = [val.length >= 8, /[A-Z]/.test(val), /[0-9]/.test(val), /[^A-Za-z0-9]/.test(val)].filter(Boolean).length;
                const cls = score <= 1 ? 'weak' : score <= 2 ? 'medium' : score === 3 ? 'medium' : 'strong';
                for (let i = 0; i < score; i++) segs[i].classList.add(cls);
                
                // Emoji feedback
                if (strengthEmoji) {
                    if (score <= 1) strengthEmoji.textContent = '🔒';
                    else if (score === 2) strengthEmoji.textContent = '🔓';
                    else if (score === 3) strengthEmoji.textContent = '😊';
                    else strengthEmoji.textContent = '🤩';
                }
            });
        }
    </script>
@endsection