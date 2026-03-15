@extends('layouts.base')
@section('title', 'Welcome')
@section('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* =================== RTL SUPPORT =================== */
        body.lang-ar {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
            text-align: right;
        }

        body.lang-en {
            font-family: 'Inter', sans-serif;
            direction: ltr;
            text-align: left;
        }

        :root {
            --orange-primary: #ff6b35;
            --orange-dark: #e55a2b;
            --orange-light: #ff9d42;
            --orange-gradient: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        }

        /* =================== NAV =================== */
        .site-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0;
            background: transparent;
            transition: all 0.4s ease;
        }

        .site-nav.scrolled {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 121, 0, 0.15);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-logo-mark {
            width: 42px;
            height: 42px;
            background: var(--orange-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(255, 121, 0, 0.4);
        }

        .nav-logo-mark svg {
            width: 22px;
            color: white;
        }

        .nav-brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lang-toggle {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 4px;
        }

        .lang-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .lang-btn.active {
            background: var(--orange-primary);
            color: white;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: var(--orange-gradient);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 14px rgba(255, 121, 0, 0.35);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 121, 0, 0.45);
            color: white;
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        /* =================== HERO =================== */
        .hero-section {
            min-height: 100vh;
            background: #0a0a0f;
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
            padding-top: 80px;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(255, 121, 0, 0.15) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            bottom: -30%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 121, 0, 0.1) 0%, transparent 70%);
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 121, 0, 0.12);
            border: 1px solid rgba(255, 121, 0, 0.25);
            color: #ff9d42;
            padding: 8px 18px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff7900;
            animation: blink 2s ease-in-out infinite;
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

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            color: white;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
        }

        .hero-title .gradient {
            background: linear-gradient(120deg, #ff7900 0%, #ffb347 50%, #ff4b00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.8;
            max-width: 540px;
            margin-bottom: 2rem;
        }

        .hero-ctas {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }

        .hero-stats {
            display: flex;
            gap: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 500;
            margin-top: 4px;
        }

        /* =================== CODE PREVIEW =================== */
        .code-preview {
            background: #141419;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        }

        .code-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 20px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .code-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .code-dot.red {
            background: #ff5f57;
        }

        .code-dot.yellow {
            background: #febc2e;
        }

        .code-dot.green {
            background: #28c840;
        }

        .code-title {
            margin-left: auto;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
            font-weight: 500;
        }

        .code-body {
            padding: 24px;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            line-height: 1.8;
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

        .code-line:nth-child(10) {
            animation-delay: 4.4s;
        }

        .code-line:nth-child(11) {
            animation-delay: 5.0s;
        }

        .code-line:nth-child(12) {
            animation-delay: 5.2s;
        }

        .code-line:nth-child(13) {
            animation-delay: 5.4s;
        }

        .code-line:nth-child(14) {
            animation-delay: 6.0s;
        }

        .code-line:nth-child(15) {
            animation-delay: 6.2s;
        }

        .code-num {
            color: rgba(255, 255, 255, 0.2);
            width: 30px;
            flex-shrink: 0;
        }

        .code-keyword {
            color: #c678dd;
        }

        .code-func {
            color: #61afef;
        }

        .code-string {
            color: #98c379;
        }

        .code-comment {
            color: #5c6370;
        }

        .code-var {
            color: #e5c07b;
        }

        /* =================== PROGRAMS SECTION =================== */
        .programs-section {
            padding: 6rem 0;
            background: #0a0a0f;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 121, 0, 0.1);
            border: 1px solid rgba(255, 121, 0, 0.2);
            color: #ff9d42;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
        }

        .section-desc {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.5);
            max-width: 600px;
            margin-bottom: 3rem;
        }

        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .program-card {
            background: #141419;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 28px;
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .program-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 121, 0, 0.08), transparent);
            opacity: 0;
            transition: opacity 0.35s;
        }

        .program-card:hover {
            border-color: rgba(255, 121, 0, 0.3);
            transform: translateY(-6px);
        }

        .program-card:hover::before {
            opacity: 1;
        }

        .program-icon {
            width: 52px;
            height: 52px;
            background: rgba(255, 121, 0, 0.12);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff9d42;
            font-size: 1.4rem;
            margin-bottom: 18px;
        }

        .program-card h5 {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .program-card p {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.9rem;
            line-height: 1.7;
            margin: 0;
        }

        .program-tag {
            display: inline-block;
            margin-top: 16px;
            padding: 4px 12px;
            background: rgba(255, 121, 0, 0.1);
            border-radius: 6px;
            font-size: 0.75rem;
            color: #ff9d42;
            font-weight: 600;
        }

        /* =================== MAP SECTION =================== */
        .map-section {
            padding: 6rem 0;
            background: #0d0d12;
        }

        .simplemaps-wrapper {
            position: relative;
            width: 100%;
            height: 500px;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(160deg, #141419 0%, #0a0a0f 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Map Interactive Info Panel (Dark Theme) */
        #mapInfoPanel {
            background: linear-gradient(135deg, #141419 0%, #0a0a0f 100%);
            border-radius: 20px;
            padding: 1.5rem;
            min-height: 500px;
            max-height: 500px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255, 121, 0, 0.15);
            transition: border-color 0.3s;
        }

        #mapInfoPanel.active {
            border-color: rgba(255, 121, 0, 0.4);
        }

        #mapInfoPanel::-webkit-scrollbar {
            width: 5px;
        }

        #mapInfoPanel::-webkit-scrollbar-thumb {
            background: rgba(255, 121, 0, 0.3);
            border-radius: 10px;
        }

        #mapInfoPanel .info-header {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: sticky;
            top: 0;
            background: #141419;
            padding: 0.5rem 0;
            z-index: 2;
            animation: panelFadeIn 0.3s ease;
        }

        .region-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ff7900;
            display: inline-block;
            margin-right: 4px;
            animation: pulse-dot 1.5s ease infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 121, 0, 0.5);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(255, 121, 0, 0);
            }
        }

        @keyframes panelFadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #mapInfoPanel .academy-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.2s, border-color 0.2s;
            border-left: 3px solid #ff7900;
            animation: slideUp 0.35s ease both;
        }

        #mapInfoPanel .academy-item:nth-child(2) {
            animation-delay: 0.08s;
        }

        #mapInfoPanel .academy-item:nth-child(3) {
            animation-delay: 0.16s;
        }

        #mapInfoPanel .academy-item:nth-child(4) {
            animation-delay: 0.24s;
        }

        #mapInfoPanel .academy-item:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 121, 0, 0.2);
        }

        .academy-stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(255, 121, 0, 0.1);
            color: #ff9d42;
        }

        /* Hide simplemaps popup */
        #map .sm_popup,
        #map .tt_sm {
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        /* =================== FEATURES SECTION =================== */
        .features-section {
            padding: 6rem 0;
            background: #0a0a0f;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: #141419;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 32px;
            transition: all 0.35s;
        }

        .feature-card:hover {
            border-color: rgba(255, 121, 0, 0.25);
            transform: translateY(-4px);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, rgba(255, 121, 0, 0.2), rgba(255, 121, 0, 0.05));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff9d42;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .feature-card h5 {
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0;
        }

        /* =================== ACADEMIES =================== */
        .academies-section {
            padding: 6rem 0;
            background: #141419;
        }

        .academies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .academy-card {
            background: #1a1a21;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 18px;
            padding: 28px;
            transition: all 0.35s;
            position: relative;
            overflow: hidden;
        }

        .academy-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--orange-gradient);
            opacity: 0;
            transition: opacity 0.35s;
        }

        .academy-card:hover {
            border-color: rgba(255, 121, 0, 0.3);
            transform: translateY(-4px);
        }

        .academy-card:hover::before {
            opacity: 1;
        }

        .academy-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .academy-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 121, 0, 0.12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff9d42;
            font-size: 1.3rem;
        }

        .academy-card h5 {
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            margin: 0;
        }

        .academy-location {
            display: flex;
            align-items: center;
            gap: 6px;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .academy-desc {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .academy-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cohort-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255, 121, 0, 0.1);
            border-radius: 8px;
            font-size: 0.8rem;
            color: #ff9d42;
            font-weight: 600;
        }

        /* =================== CTA =================== */
        .cta-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .cta-section h2 {
            font-size: clamp(2rem, 3vw, 2.8rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta-dark {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 40px;
            background: #0a0a0f;
            color: white;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-cta-dark:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: white;
        }

        /* =================== FOOTER =================== */
        .site-footer {
            background: #08090d;
            padding: 3rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 600;
        }

        .footer-brand span {
            color: white;
        }

        .footer-links {
            display: flex;
            gap: 24px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #ff9d42;
        }

        /* RTL */
        body.lang-ar .nav-inner,
        body.lang-ar .nav-actions,
        body.lang-ar .hero-ctas,
        body.lang-ar .hero-stats,
        body.lang-ar .nav-logo {
            flex-direction: row-reverse;
        }

        body.lang-ar .hero-stat {
            text-align: right;
        }

        body.lang-ar .code-line {
            flex-direction: row-reverse;
        }

        body.lang-ar .academy-footer {
            flex-direction: row-reverse;
        }
    </style>
@endsection

@section('body')
    {{-- ==================== NAV ==================== --}}
    <nav class="site-nav" id="mainNav">
        <div class="container">
            <div class="nav-inner">
                <a href="/" class="nav-logo">
                    <div class="nav-logo-mark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <polyline points="4 17 10 11 4 5"></polyline>
                            <line x1="12" y1="19" x2="20" y2="19"></line>
                        </svg>
                    </div>
                    <span class="nav-brand" data-en="Orange Academy" data-ar="أكاديمية أورنج">Orange Academy</span>
                </a>
                <div class="nav-actions">
                    <div class="lang-toggle">
                        <button class="lang-btn active" id="btnEn" onclick="setLang('en')">EN</button>
                        <div style="width:1px;height:16px;background:rgba(255,255,255,0.2);margin:0 4px;"></div>
                        <button class="lang-btn" id="btnAr" onclick="setLang('ar')">AR</button>
                    </div>
                    @auth
                        <a href="{{ route('student.dashboard') }}" class="btn-hero-secondary"><i class="bi bi-grid-1x2"></i>
                            <span data-en="Dashboard" data-ar="لوحة التحكم">Dashboard</span></a>
                    @else
                        <a href="{{ route('login') }}" class="btn-hero-secondary"><span data-en="Sign In"
                                data-ar="تسجيل الدخول">Sign In</span></a>
                        <a href="{{ route('register') }}" class="btn-hero-primary"><i class="bi bi-rocket-takeoff"></i> <span
                                data-en="Apply Now" data-ar="سجّل الآن">Apply Now</span></a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ==================== HERO ==================== --}}
    <section class="hero-section">
        <div class="hero-bg">
            <div class="hero-grid"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <div class="hero-badge">
                        <span class="dot"></span>
                        <span data-en="#1 Coding Academy in Jordan" data-ar="#1 أكاديمية برمجة في الأردن">#1 Coding Academy
                            in Jordan</span>
                    </div>
                    <h1 class="hero-title">
                        <span data-en="Learn to" data-ar="تعلم أن">Learn to</span><br>
                        <span class="gradient" data-en="Code Your Future" data-ar="تبرمج مستقبلكت">Code Your Future</span>
                    </h1>
                    <p class="hero-desc"
                        data-en="Master modern programming languages and frameworks. Join thousands of students transforming their careers with practical, industry-ready skills in PHP, Laravel, Node.js, and more."
                        data-ar="أتقن لغات البرمجة الحديثة. انضم لآلاف الطلاب الذين يغيرون مسيرتهم المهنية بمهارات عملية وجاهزة للصناعة في PHP وLaravel وNode.js والمزيد.">
                        Master modern programming languages and frameworks. Join thousands of students transforming their
                        careers with practical, industry-ready skills in PHP, Laravel, Node.js, and more.</p>
                    <div class="hero-ctas">
                        <a href="{{ route('register') }}" class="btn-hero-primary"
                            style="padding:14px 32px;font-size:1rem;">
                            <i class="bi bi-rocket-takeoff"></i>
                            <span data-en="Start Learning" data-ar="ابدأ التعلم">Start Learning</span>
                        </a>
                        <a href="#programs" class="btn-hero-secondary" style="padding:14px 32px;font-size:1rem;">
                            <i class="bi bi-play-circle"></i>
                            <span data-en="Explore Programs" data-ar="استكشف البرامج">Explore Programs</span>
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-number">5000+</div>
                            <div class="hero-stat-label" data-en="Students" data-ar="طالب">Students</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number">50+</div>
                            <div class="hero-stat-label" data-en="Courses" data-ar="مقرر">Courses</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number">95%</div>
                            <div class="hero-stat-label" data-en="Success Rate" data-ar="نسبة النجاح">Success Rate</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="code-preview">
                        <div class="code-header">
                            <span class="code-dot red"></span>
                            <span class="code-dot yellow"></span>
                            <span class="code-dot green"></span>
                            <span class="code-title">app/Http/Controllers/StudentController.php</span>
                        </div>
                        <div class="code-body">
                            <div class="code-line"><span class="code-num">1</span><span class="code-comment">// Welcome to
                                    Orange Academy</span></div>
                            <div class="code-line"><span class="code-num">2</span><span class="code-keyword">class</span>
                                <span class="code-func">StudentController</span> <span class="code-keyword">extends</span>
                                Controller
                            </div>
                            <div class="code-line"><span class="code-num">3</span>{</div>
                            <div class="code-line"><span class="code-num">4</span> <span class="code-keyword">public
                                    function</span> <span class="code-func">learn</span>()</div>
                            <div class="code-line"><span class="code-num">5</span> {</div>
                            <div class="code-line"><span class="code-num">6</span> $<span class="code-var">skills</span> =
                                [<span class="code-string">'PHP'</span>, <span class="code-string">'Laravel'</span>, <span
                                    class="code-string">'Node.js'</span>];</div>
                            <div class="code-line"><span class="code-num">7</span> </div>
                            <div class="code-line"><span class="code-num">8</span> <span class="code-keyword">foreach</span>
                                ($skills <span class="code-keyword">as</span> $skill) {</div>
                            <div class="code-line"><span class="code-num">9</span> $<span
                                    class="code-var">this</span>-><span class="code-func">master</span>($skill);</div>
                            <div class="code-line"><span class="code-num">10</span> $<span
                                    class="code-var">career</span>-><span class="code-func">transform</span>();</div>
                            <div class="code-line"><span class="code-num">11</span> }</div>
                            <div class="code-line"><span class="code-num">12</span> </div>
                            <div class="code-line"><span class="code-num">13</span> <span class="code-keyword">return</span>
                                <span class="code-func">success</span>();
                            </div>
                            <div class="code-line"><span class="code-num">14</span> }</div>
                            <div class="code-line"><span class="code-num">15</span>}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== VIDEO ==================== --}}
    <section class="video-section" style="padding: 60px 0; background: #0a0a0f;">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge" data-en="Learn More" data-ar="اعرف المزيد">Learn More</div>
                <h2 class="section-title" data-en="Discover Orange Academy" data-ar="اكتشف أكاديمية أورنج">Discover Orange Academy</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="video-container" style="position: relative; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(255, 121, 0, 0.2);">
                        <iframe 
                            width="100%" 
                            height="500" 
                            src="https://www.youtube.com/embed/TSY3Yob1tVQ?si=dETe88w-7bMg1V0g" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            referrerpolicy="strict-origin-when-cross-origin" 
                            allowfullscreen
                            style="display: block;">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== PROGRAMS ==================== --}}
    <section class="programs-section" id="programs">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge" data-en="Our Programs" data-ar="برامجنا">Our Programs</div>
                <h2 class="section-title" data-en="Master Modern Technologies" data-ar="أتقن التقنيات الحديثة">Master Modern
                    Technologies</h2>
                <p class="section-desc"
                    data-en="Comprehensive courses designed to make you job-ready in the most in-demand technologies."
                    data-ar="دورات شاملة مصممة لتجعلك جاهزاً للعمل في أكثر التقنيات المطلوبة.">Comprehensive courses
                    designed to make you job-ready in the most in-demand technologies.</p>
            </div>
            <div class="programs-grid">
                <div class="program-card">
                    <div class="program-icon"><i class="bi bi-filetype-php"></i></div>
                    <h5>PHP Development</h5>
                    <p data-en="Master server-side programming with PHP. Build dynamic websites and applications from scratch."
                        data-ar="أتقن البرمجة من جهة الخادم مع PHP. أنشئ مواقع وتطبيقات ديناميكية من الصفر.">Master
                        server-side programming with PHP. Build dynamic websites and applications from scratch.</p>
                    <span class="program-tag">Beginner Friendly</span>
                </div>
                <div class="program-card">
                    <div class="program-icon"><i class="bi bi-code-slash"></i></div>
                    <h5>Laravel Framework</h5>
                    <p data-en="Learn the most popular PHP framework. Build robust, secure web applications fast."
                        data-ar="أفضل إطار عمل PHP. أنشئ تطبيقات ويب قوية وآمنة بسرعة.">Learn the most popular PHP
                        framework. Build robust, secure web applications fast.</p>
                    <span class="program-tag">Most Popular</span>
                </div>
                <div class="program-card">
                    <div class="program-icon"><i class="bi bi-braces"></i></div>
                    <h5>Node.js & Express</h5>
                    <p data-en="Build scalable backend systems with JavaScript. Create APIs and real-time applications."
                        data-ar="أنشئ أنظمة خلفية قابلة للتطوير باستخدام JavaScript. أنشئ واجهات برمجة تطبيقات وتطبيقات فورية.">
                        Build scalable backend systems with JavaScript. Create APIs and real-time applications.</p>
                    <span class="program-tag">Full Stack</span>
                </div>
                <div class="program-card">
                    <div class="program-icon"><i class="bi bi-diagram-3"></i></div>
                    <h5> Database Design</h5>
                    <p data-en="Master MySQL and PostgreSQL. Design efficient databases and optimize queries."
                        data-ar="أتقن MySQL وPostgreSQL. صمم قواعد بيانات فعالة وحسّن الاستعلامات.">Master MySQL and
                        PostgreSQL. Design efficient databases and optimize queries.</p>
                    <span class="program-tag">Essential Skill</span>
                </div>
                <div class="program-card">
                    <div class="program-icon"><i class="bi bi-cloud"></i></div>
                    <h5>Cloud & DevOps</h5>
                    <p data-en="Deploy and manage applications in the cloud. Learn Docker, AWS, and CI/CD pipelines."
                        data-ar="انشر وإدارة التطبيقات في السحابة. تعلم Docker وAWS وأنابيب CI/CD.">Deploy and manage
                        applications in the cloud. Learn Docker, AWS, and CI/CD pipelines.</p>
                    <span class="program-tag">Industry Ready</span>
                </div>
                <div class="program-card">
                    <div class="program-icon"><i class="bi bi-phone"></i></div>
                    <h5>Mobile Development</h5>
                    <p data-en="Build cross-platform mobile apps with React Native. Reach users on iOS and Android."
                        data-ar="أنشئ تطبيقات هاتفية متعددة المنصات باستخدام React Native. reach المستخدمين على iOS وAndroid.">
                        Build cross-platform mobile apps with React Native. Reach users on iOS and Android.</p>
                    <span class="program-tag">Trending</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== MAP ==================== --}}
    <section class="map-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge" data-en="Our Locations" data-ar="مواقعنا">Our Locations</div>
                <h2 class="section-title" data-en="Join to Near Academy to you" data-ar="انضم لاقرب أكاديمية الك">Join a
                    Campus Near You</h2>
                <p class="section-desc"
                    data-en="Click on a highlighted region on the map to view available academies and apply!"
                    data-ar="انقر على المنطقة المميزة على الخريطة لعرض الأكاديميات المتاحة والتسجيل!">Click on a highlighted
                    region on the map to view available academies and apply!</p>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-7">
                    <div class="simplemaps-wrapper h-100">
                        <div id="map"></div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div id="mapInfoPanel" class="h-100">
                        <div class="text-center py-5" id="mapDefaultMessage" style="margin:auto;">
                            <div
                                style="width:80px;height:80px;border-radius:50%;background:rgba(255,121,0,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                                <i class="bi bi-hand-index-thumb" style="font-size: 2.2rem; color: #ff7900;"></i>
                            </div>
                            <h5 class="fw-bold text-white mb-2" data-en="Select a Region" data-ar="اختر منطقة">Select a
                                Region</h5>
                            <p style="color:rgba(255,255,255,0.5);" class="small"
                                data-en="Click on a colored region on the map to see available academies."
                                data-ar="انقر على منطقة ملونة على الخريطة لرؤية الأكاديميات المتاحة.">Click on a colored
                                region on the map to see available academies.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Map Data Generation --}}
    @php
        // Organize academies by city location for the interactive map
        $academyMapData = [];
        $citiesMap = ['JOR849' => 'Aqaba', 'JOR851' => 'Amman', 'JOR854' => 'Irbid', 'JOR857' => 'Balqa', 'JOR860' => 'Zarqa'];
        if (isset($academies) && count($academies)) {
            foreach ($academies as $ac) {
                // Normalize location to base city name
                $loc = trim($ac->location);
                if (stripos($loc, 'amman') !== false)
                    $loc = 'Amman';
                elseif (stripos($loc, 'irbid') !== false)
                    $loc = 'Irbid';
                elseif (stripos($loc, 'zarqa') !== false)
                    $loc = 'Zarqa';
                elseif (stripos($loc, 'balqa') !== false)
                    $loc = 'Balqa';
                elseif (stripos($loc, 'aqaba') !== false)
                    $loc = 'Aqaba';

                $academyMapData[$loc][] = [
                    'id' => $ac->id,
                    'name' => $ac->name,
                    'location' => $ac->location,
                    'cohorts_count' => $ac->cohorts()->count()
                ];
            }
        }
    @endphp

    <script src="{{ asset('map/mapdata.js') }}"></script>
    <script>
        // === MUST be defined BEFORE countrymap.js loads ===
        var __mapAcademyData = @json($academyMapData);
        var __mapStateToCity = @json($citiesMap);

        // Map city names to state IDs
        var __cityToState = {};
        for (var sid in __mapStateToCity) {
            __cityToState[__mapStateToCity[sid]] = sid;
        }

        // Disable popups
        simplemaps_countrymap_mapdata.main_settings.popups = 'off';

        // Update mapdata styling & register clicks
        for (var stateId in simplemaps_countrymap_mapdata.state_specific) {
            var stateData = simplemaps_countrymap_mapdata.state_specific[stateId];
            if (__mapStateToCity[stateId]) {
                var cityName = __mapStateToCity[stateId];
                var cityAcademies = __mapAcademyData[cityName] || [];

                if (cityAcademies.length > 0) {
                    stateData.color = 'rgba(255, 121, 0, 0.4)';
                    stateData.hover_color = 'rgba(255, 121, 0, 1)';
                    stateData.url = "javascript:showPublicCityInfoPanel('" + cityName.replace(/'/g, "\\'") + "');";
                } else {
                    stateData.url = ""; // Disable click
                    stateData.color = '#333333';
                    stateData.hover_color = '#444444';
                }
            }
        }

        // Apply same to locations (pins)
        for (var locId in simplemaps_countrymap_mapdata.locations) {
            var loc = simplemaps_countrymap_mapdata.locations[locId];
            var pinCity = loc.name;
            if (pinCity && __mapAcademyData[pinCity]) {
                loc.color = '#ff7900';
                loc.url = "javascript:showPublicCityInfoPanel('" + pinCity.replace(/'/g, "\\'") + "');";
            }
        }

        function simplemaps_countrymap_click(id) {
            var cityName = __mapStateToCity[id];
            if (cityName) {
                showPublicCityInfoPanel(cityName);
            }
        }

        function showPublicCityInfoPanel(cityName) {
            var panel = document.getElementById('mapInfoPanel');
            var cityAcademies = __mapAcademyData[cityName] || [];

            panel.classList.add('active');

            var html = '<div class="info-header">' +
                '<span class="region-dot"></span>' +
                '<i class="bi bi-geo-alt-fill" style="color:#ff7900;"></i> <span data-en="' + cityName + '" data-ar="' + cityName + '">' + cityName + '</span>' +
                '<span class="ms-auto academy-stat-badge">' +
                '<i class="bi bi-building"></i> ' + cityAcademies.length + ' Academies</span></div>';

            if (cityAcademies.length === 0) {
                html += '<div class="text-muted small text-center py-5"><i class="bi bi-map" style="font-size:2.5rem;color:rgba(255,255,255,0.1);"></i><br><span class="mt-3 d-block text-white-50">No academies in this region yet.</span></div>';
            } else {
                cityAcademies.forEach(function (a) {
                    html += '<div class="academy-item">';
                    html += '  <div class="mb-3">';
                    html += '    <h5 class="text-white fw-bold mb-1" style="font-size:1.1rem;">' + a.name + '</h5>';
                    html += '    <div style="color:rgba(255,255,255,0.5);font-size:0.85rem;"><i class="bi bi-geo-alt me-1"></i>' + a.location + '</div>';
                    html += '  </div>';
                    html += '  <div class="d-flex align-items-center justify-content-between mt-3">';
                    html += '    <div class="d-flex align-items-center gap-2" style="font-size:0.85rem;color:rgba(255,255,255,0.7);">';
                    html += '      <i class="bi bi-mortarboard" style="color:#ff7900;"></i> ' + a.cohorts_count + (a.cohorts_count === 1 ? ' Cohort' : ' Cohorts');
                    html += '    </div>';
                    html += '    <a href="{{ route("register") }}" class="btn-hero-primary" style="padding: 8px 18px; font-size: 0.85rem;">Apply Now <i class="bi bi-arrow-right-short ms-1"></i></a>';
                    html += '  </div>';
                    html += '</div>';
                });
            }

            panel.innerHTML = html;

            // RTL text override if needed based on body class
            var isAr = document.body.classList.contains('lang-ar');
            if (isAr) {
                panel.querySelectorAll('[data-en]').forEach(function (el) { el.textContent = el.getAttribute('data-ar'); });
                var btns = panel.querySelectorAll('a.btn-hero-primary');
                btns.forEach(function (btn) { btn.innerHTML = 'سجّل الآن <i class="bi bi-arrow-left-short ms-1"></i>'; });
            }

            panel.scrollTop = 0;
        }
    </script>
    <script src="{{ asset('map/countrymap.js') }}"></script>

    {{-- ==================== FEATURES ==================== --}}
    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge" data-en="Why Choose Us" data-ar="لماذا تختارنا">Why Choose Us</div>
                <h2 class="section-title" data-en="Your Path to Tech Success" data-ar="طريقك نحو النجاح التقني">Your Path to
                    Tech Success</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-person-check"></i></div>
                    <h5 data-en="Expert Instructors" data-ar="معلمون خبراء">Expert Instructors</h5>
                    <p data-en="Learn from industry professionals with years of real-world experience in leading tech companies."
                        data-ar="تعلم من متخصصين في الصناعة 具有سنوات من الخبرة العملية في الشركات التقنية الرائدة.">Learn
                        from industry professionals with years of real-world experience in leading tech companies.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-laptop"></i></div>
                    <h5 data-en="Hands-on Projects" data-ar="مشاريع عملية">Hands-on Projects</h5>
                    <p data-en="Build real-world applications. Gain practical experience that employers are looking for."
                        data-ar="أنشئ تطبيقات عملية. اكتسب خبرة عملية يبحث عنها أصحاب العمل.">Build real-world applications.
                        Gain practical experience that employers are looking for.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-briefcase"></i></div>
                    <h5 data-en="Career Support" data-en="دعم المسار المهني">Career Support</h5>
                    <p data-en="Get help with job placement. Connect with our hiring network of tech companies."
                        data-ar="احصل على المساعدة في التوظيف. تواصل مع شبكة التوظيف لدينا من الشركات التقنية.">Get help
                        with job placement. Connect with our hiring network of tech companies.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-people"></i></div>
                    <h5 data-en="Community" data-ar="مجتمع">Community</h5>
                    <p data-en="Join a network of like-minded learners. Get support from peers and mentors."
                        data-ar="انضم إلى شبكة من المتعلمين المتشابهين في التفكير. احصل على الدعم من الزملاء والمMentors.">
                        Join a network of like-minded learners. Get support from peers and mentors.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-award"></i></div>
                    <h5 data-en="Certificates" data-ar="شهادات">Certificates</h5>
                    <p data-en="Earn recognized certificates upon completion. Boost your resume and career prospects."
                        dataار="احصل على شهادات معترف بها عند الإكمال. عزز سيرتك الذاتية وآفاقك المهنية.">Earn recognized
                        certificates upon completion. Boost your resume and career prospects.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-clock-history"></i></div>
                    <h5 data-en="Flexible Schedule" data-ar="جدول مرن">Flexible Schedule</h5>
                    <p data-en="Study at your own pace. Choose from full-time, part-time, and weekend options."
                        data-ar="ادرس بسرعتك الخاصة. اختر من بين الخيارات بدوام كامل أو جزئي أو عطلة نهاية الأسبوع.">Study
                        at your own pace. Choose from full-time, part-time, and weekend options.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- {{-- ==================== ACADEMIES ==================== --}}
    <section class="academies-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge" data-en="Our Academies" data-ar="أكاديمياتنا">Our Academies</div>
                <h2 class="section-title" data-en="Find Your Campus" data-ar="اعثر على حرمك الجامعي">Find Your Campus</h2>
            </div>
            <div class="academies-grid">
                @forelse($academies ?? [] as $academy)
                    <div class="academy-card" data-location="{{ $academy->location }}">
                        <div class="academy-header">
                            <div class="academy-icon"><i class="bi bi-building"></i></div>
                            <h5>{{ $academy->name }}</h5>
                        </div>
                        <div class="academy-location">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $academy->location }}
                        </div>
                        <p class="academy-desc"
                            data-en="State-of-the-art facility with modern computers and collaborative learning spaces."
                            data-ar="مرفق حديث بأجهزة كمبيوتر متقدمة ومساحات تعليمية تعاونية.">State-of-the-art facility with
                            modern computers and collaborative learning spaces.</p>
                        <div class="academy-footer">
                            <span class="cohort-badge">
                                <i class="bi bi-mortarboard-fill"></i>
                                {{ $academy->cohorts->count() }} {{ $academy->cohorts->count() == 1 ? 'Cohort' : 'Cohorts' }}
                            </span>
                            <a href="{{ route('register') }}" class="btn-hero-primary"
                                style="padding:8px 20px;font-size:0.85rem;">
                                <span data-en="Apply" data-ar="سجّل">Apply</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p style="color:rgba(255,255,255,0.5);" data-en="No academies available yet. Check back soon!"
                            data-ar="لا توجد أكاديميات متاحة بعد. تابعنا قريباً!">No academies available yet. Check back soon!
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section> -->

    {{-- ==================== CTA ==================== --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 data-en="Ready to Start Your Coding Journey?" data-ar="هل أنت مستعد لبدء رحلة البرمجة؟">Ready to Start
                    Your Coding Journey?</h2>
                <p data-en="Join thousands of students who have transformed their careers. Apply now and get 20% off your first course!"
                    data-ar="انضم لآلاف الطلاب الذين غيروا مسيراتهم المهنية. سجّل الآن واحصل على خصم 20% على دورتك الأولى!">
                    Join thousands of students who have transformed their careers. Apply now and get 20% off your first
                    course!</p>
                <a href="{{ route('register') }}" class="btn-cta-dark">
                    <span data-en="Apply Now - It's Free" data-ar="سجّل الآن - مجاناً">Apply Now - It's Free</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="nav-logo-mark" style="width:32px;height:32px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            style="width:16px;">
                            <polyline points="4 17 10 11 4 5"></polyline>
                            <line x1="12" y1="19" x2="20" y2="19"></line>
                        </svg>
                    </div>
                    <span>Orange Academy</span>
                </div>
                <div class="footer-links">
                    <a href="#"><span data-en="About" data-ar="من نحن">About</span></a>
                    <a href="#"><span data-en="Contact" data-ar="اتصل بنا">Contact</span></a>
                    <a href="#"><span data-en="Privacy" data-ar="الخصوصية">Privacy</span></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Nav scroll
        window.addEventListener('scroll', () => {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 40);
        });

        // Language - Default to English, only change if explicitly set
        function setLang(lang) {
            document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn' + lang.charAt(0).toUpperCase() + lang.slice(1)).classList.add('active');
            document.body.classList.remove('lang-en', 'lang-ar');
            document.body.classList.add('lang-' + lang);
            localStorage.setItem('lang', lang);
            document.querySelectorAll('[data-en]').forEach(el => {
                el.textContent = el.getAttribute('data-' + lang);
            });
        }
        
        // Always default to English on page load, only use saved language if explicitly set by user
        const saved = localStorage.getItem('lang');
        if (saved === 'ar') {
            setLang('ar');
        } else {
            setLang('en');
        }

        // Loop typing animation
        setInterval(() => {
            const lines = document.querySelectorAll('.code-line');
            lines.forEach(line => {
                line.style.animation = 'none';
                void line.offsetWidth; // trigger reflow
                line.style.animation = '';
            });
        }, 8500);
    </script>
@endsection