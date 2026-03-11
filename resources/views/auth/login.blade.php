@extends('layouts.base')
@section('title', 'Sign In — Orange Academy')
@section('body')
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800&family=Fira+Code:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --orange-primary: #ff6b35;
            --orange-dark: #e55a2b;
            --orange-light: #ff9d42;
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
        }

        body.lang-ar {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
        }

        .auth-page {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT PANEL ── */
        .auth-showcase {
            display: none;
            flex: 0 0 48%;
            background: #0a0a0f;
            position: relative;
            overflow: hidden;
            padding: 3rem;
            flex-direction: column;
            justify-content: space-between;
        }

        @media (min-width: 1024px) {
            .auth-showcase {
                display: flex;
            }
        }

        .showcase-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255, 121, 0, 0.18) 0%, transparent 60%),
                radial-gradient(circle at 80% 20%, rgba(255, 121, 0, 0.08) 0%, transparent 50%);
        }

        .showcase-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .showcase-content {
            position: relative;
            z-index: 2;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 3rem;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(255, 121, 0, 0.4);
        }

        .brand-icon svg {
            width: 22px;
            color: white;
        }

        .brand-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
        }

        .showcase-heading {
            font-size: clamp(1.8rem, 2.5vw, 2.4rem);
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
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        /* Code window */
        .code-window {
            background: #0f0f16;
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .code-titlebar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 18px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot-r {
            background: #ff5f57;
        }

        .dot-y {
            background: #febc2e;
        }

        .dot-g {
            background: #28c840;
        }

        .code-filename {
            margin-left: auto;
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.78rem;
            font-family: 'Fira Code', monospace;
        }

        .code-body {
            padding: 20px 24px;
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            line-height: 2;
        }

        @keyframes typing-reveal {
            from {
                clip-path: inset(0 100% 0 0);
                opacity: 0;
            }

            1% {
                opacity: 1;
            }

            to {
                clip-path: inset(0 0 0 0);
                opacity: 1;
            }
        }

        .code-line {
            display: flex;
            gap: 16px;
            opacity: 0;
            clip-path: inset(0 100% 0 0);
            animation: typing-reveal 0.6s steps(30, end) forwards;
        }

        .code-line:nth-child(1) {
            animation-delay: 0.2s;
        }

        .code-line:nth-child(2) {
            animation-delay: 0.8s;
        }

        .code-line:nth-child(3) {
            animation-delay: 1.4s;
        }

        .code-line:nth-child(4) {
            animation-delay: 1.6s;
        }

        .code-line:nth-child(5) {
            animation-delay: 2.2s;
        }

        .code-line:nth-child(6) {
            animation-delay: 2.4s;
        }

        .code-line:nth-child(7) {
            animation-delay: 3.0s;
        }

        .code-line:nth-child(8) {
            animation-delay: 3.2s;
        }

        .code-line:nth-child(9) {
            animation-delay: 3.8s;
        }

        .ln {
            color: rgba(255, 255, 255, 0.2);
            min-width: 18px;
            text-align: right;
            user-select: none;
        }

        .kw {
            color: #c678dd;
        }

        .fn {
            color: #61afef;
        }

        .str {
            color: #98c379;
        }

        .cm {
            color: #5c6370;
        }

        .vr {
            color: #e5c07b;
        }

        .op {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Tech chips */
        .tech-chips {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .chip {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.55);
            font-weight: 500;
            transition: all 0.3s;
        }

        .chip:hover {
            border-color: rgba(255, 121, 0, 0.4);
            color: #ff9d42;
        }

        .chip-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff7900;
        }

        /* ── RIGHT PANEL (form) ── */
        .auth-form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2.5rem 2rem;
            background: #0d0d14;
            position: relative;
            overflow: hidden;
        }

        .auth-form-panel::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 121, 0, 0.06) 0%, transparent 70%);
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
        }

        .form-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
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
        }

        .form-brand-icon svg {
            width: 18px;
            color: white;
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
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
        }

        .form-subheading {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        /* Inputs */
        .input-group-custom {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 8px;
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

        .auth-input:focus+.input-icon,
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

        /* Submit button */
        .btn-auth {
            width: 100%;
            padding: 15px;
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
            margin-top: 0.5rem;
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

        .form-check-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.875rem;
        }

        .check-label input[type="checkbox"] {
            accent-color: #ff7900;
            width: 16px;
            height: 16px;
        }

        /* Divider */
        .or-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
        }

        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
        }

        .or-divider span {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.8rem;
            white-space: nowrap;
        }

        /* Floating badge */
        .floating-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 121, 0, 0.1);
            border: 1px solid rgba(255, 121, 0, 0.2);
            color: #ff9d42;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
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

        /* Error alert */
        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff8080;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
    </style>

    <div class="auth-page">
        {{-- ── LEFT Showcase Panel ── --}}
        <div class="auth-showcase">
            <div class="showcase-bg"></div>
            <div class="showcase-grid"></div>
            <div class="showcase-content">
                <a href="/" class="brand-mark">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <polyline points="4 17 10 11 4 5"></polyline>
                            <line x1="12" y1="19" x2="20" y2="19"></line>
                        </svg>
                    </div>
                    <span class="brand-name" data-en="Orange Academy" data-ar="أكاديمية أورنج">Orange Academy</span>
                </a>

                <div>
                    <div class="floating-badge"><span class="dot-live"></span> <span data-en="Accepting Applications"
                            data-ar="يقبل الطلبات">Accepting Applications</span></div>
                    <h2 class="showcase-heading">
                        <span data-en="Code Your Path to" data-ar="برمج طريقك نحو">Code Your Path to</span><br>
                        <span class="gradient" data-en="Tech Excellence" data-ar="التميز التقني">Tech Excellence</span>
                    </h2>
                    <p class="showcase-desc"
                        data-en="Master PHP, Laravel, Node.js and more. Join our intensive programs designed by industry experts."
                        data-ar="أتقن PHP وLaravel وNode.js والمزيد. انضم إلى برامجنا المكثفة التي صممها خبراء الصناعة.">
                        Master PHP, Laravel, Node.js and more. Join our intensive programs designed by industry experts.</p>

                    <div class="code-window">
                        <div class="code-titlebar">
                            <span class="dot dot-r"></span><span class="dot dot-y"></span><span class="dot dot-g"></span>
                            <span class="code-filename">routes/web.php</span>
                        </div>
                        <div class="code-body">
                            <div class="code-line"><span class="ln">1</span><span class="cm">// your journey starts
                                    here</span></div>
                            <div class="code-line"><span class="ln">2</span><span class="kw">Route</span><span
                                    class="op">::</span><span class="fn">get</span><span class="op">(</span><span
                                    class="str">'/dashboard'</span><span class="op">, function () {</span></div>
                            <div class="code-line"><span class="ln">3</span><span
                                    class="op">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="kw">return</span> <span
                                    class="fn">view</span><span class="op">(</span><span
                                    class="str">'student.home'</span><span class="op">);</span></div>
                            <div class="code-line"><span class="ln">4</span><span class="op">})</span><span
                                    class="fn">.middleware</span><span class="op">(</span><span
                                    class="str">'auth'</span><span class="op">);</span></div>
                            <div class="code-line"><span class="ln">5</span>&nbsp;</div>
                            <div class="code-line"><span class="ln">6</span><span class="vr">$skills</span><span class="op">
                                    = [</span><span class="str">'PHP'</span><span class="op">,</span> <span
                                    class="str">'Laravel'</span><span class="op">,</span> <span
                                    class="str">'Node.js'</span><span class="op">];</span></div>
                            <div class="code-line"><span class="ln">7</span><span class="kw">foreach</span><span class="op">
                                    (</span><span class="vr">$skills</span><span class="op"> as </span><span
                                    class="vr">$skill</span><span class="op">) {</span></div>
                            <div class="code-line"><span class="ln">8</span><span
                                    class="op">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="vr">$you</span><span
                                    class="op">-></span><span class="fn">master</span><span class="op">(</span><span
                                    class="vr">$skill</span><span class="op">);</span></div>
                            <div class="code-line"><span class="ln">9</span><span class="op">}</span></div>
                        </div>
                    </div>

                    <div class="tech-chips">
                        <span class="chip"><span class="chip-dot"></span>PHP 8.3</span>
                        <span class="chip"><span class="chip-dot"></span>Laravel 12</span>
                        <span class="chip"><span class="chip-dot"></span>Node.js</span>
                        <span class="chip"><span class="chip-dot"></span>MySQL</span>
                        <span class="chip"><span class="chip-dot"></span>Vue.js</span>
                        <span class="chip"><span class="chip-dot"></span>Docker</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RIGHT Form Panel ── --}}
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

                <div class="floating-badge d-lg-none"><span class="dot-live"></span> Orange Academy</div>
                <h1 class="form-heading" data-en="Welcome back" data-ar="مرحباً بعودتك">Welcome back</h1>
                <p class="form-subheading" data-en="Sign in to continue your learning journey"
                    data-ar="سجّل دخولك لمتابعة رحلة التعلم">Sign in to continue your learning journey</p>

                <form method="POST" action="{{ route('login') }}" autocomplete="on">
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
                        <label class="input-label" data-en="Password" data-ar="كلمة المرور">Password</label>
                        <div class="input-wrap">
                            <input type="password" name="password" id="password" autocomplete="current-password"
                                class="auth-input password @error('password') is-invalid @enderror" placeholder="••••••••"
                                required>
                            <i class="bi bi-lock input-icon"></i>
                        </div>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-check-row">
                        <label class="check-label">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span data-en="Remember me" data-ar="تذكّرني">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span data-en="Sign In" data-ar="تسجيل الدخول">Sign In</span>
                    </button>
                </form>

                <div class="or-divider"><span data-en="New to Orange Academy?" data-ar="جديد في أكاديمية أورنج؟">New to
                        Orange Academy?</span></div>

                <p class="form-footer">
                    <a href="{{ route('register') }}" data-en="Create your free account →"
                        data-ar="→ أنشئ حسابك المجاني">Create your free account →</a>
                </p>
            </div>
        </div>
    </div>

    <script>     function setLang(lang) {         document.body.classList.toggle('lang-ar', lang === 'ar');         document.getElementById('btnEN').classList.toggle('active', lang === 'en');         document.getElementById('btnAR').classList.toggle('active', lang === 'ar');         localStorage.setItem('lang', lang);         document.querySelectorAll('[data-en]').forEach(el => {             el.textContent = el.getAttribute('data-' + lang) || el.getAttribute('data-en');         });     }     const saved = localStorage.getItem('lang') || 'en';     setLang(saved);
         // Loop typing animation     setInterval(() => {         const lines = document.querySelectorAll('.code-line');         lines.forEach(line => {             line.style.animation = 'none';             void line.offsetWidth; // trigger reflow             line.style.animation = '';         });     }, 6000); // Shorter interval since login code is fewer lines
    </script>
@endsection