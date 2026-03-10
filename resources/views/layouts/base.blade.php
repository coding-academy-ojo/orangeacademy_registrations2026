<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Orange Academy') - Registration Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        /* ===== GLOBAL BILINGUAL SYSTEM ===== */
        body.lang-ar {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
        }

        body.lang-en {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            direction: ltr;
        }

        /* Language toggle button component */
        .lang-toggle {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            overflow: hidden;
        }

        .lang-toggle.light {
            background: rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0.12);
        }

        .lang-btn {
            padding: 6px 13px;
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.45);
            cursor: pointer;
            border: none;
            background: transparent;
            transition: all 0.2s ease;
            letter-spacing: 0.04em;
        }

        .lang-toggle.light .lang-btn {
            color: rgba(0, 0, 0, 0.35);
        }

        .lang-btn.active {
            background: var(--orange-gradient);
            color: white !important;
            box-shadow: 0 2px 8px rgba(255, 121, 0, 0.4);
        }

        .lang-divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.12);
            height: 26px;
        }

        .lang-toggle.light .lang-divider {
            background: rgba(0, 0, 0, 0.1);
        }

        /* RTL sidebar adjustments */
        body.lang-ar .admin-sidebar {
            left: auto !important;
            right: 0 !important;
            border-right: none !important;
            border-left: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        body.lang-ar .admin-main {
            margin-left: 0 !important;
            margin-right: 280px !important;
        }

        body.lang-ar .admin-sidebar .nav-link:hover {
            transform: translateX(-4px) !important;
        }

        body.lang-ar .admin-sidebar .nav-link {
            flex-direction: row-reverse;
        }

        /* RTL student layout */
        body.lang-ar .student-navbar .navbar-nav {
            flex-direction: row-reverse;
        }
    </style>
    <style>
        :root {
            /* Premium Orange Palette */
            --orange-primary: #ff7900;
            --orange-dark: #cc6100;
            --orange-light: #ff9d42;
            --orange-gradient: linear-gradient(135deg, #ff7900 0%, #ff4b00 100%);
            --orange-glow: rgba(255, 121, 0, 0.4);

            /* Status Colors (SaaS Modern) */
            --orange-success: #10b981;
            --orange-info: #3b82f6;
            --orange-warning: #f59e0b;
            --orange-danger: #ef4444;

            /* Backgrounds & Surfaces */
            --orange-bg: #f8fafc;
            --orange-surface: rgba(255, 255, 255, 0.85);
            --orange-surface-solid: #ffffff;

            /* Borders & Shadows */
            --orange-border: rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 2px 8px -2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px -4px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 20px 40px -8px rgba(0, 0, 0, 0.08);
            --shadow-floating: 0 30px 60px -12px rgba(255, 121, 0, 0.15);

            /* Glassmorphism */
            --glass-blur: blur(16px);
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: 1px solid rgba(255, 255, 255, 0.6);

            /* Typography Scale */
            --text-heading: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;

            /* Geometry */
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-pill: 999px;

            /* Animation */
            --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--orange-bg);
            background-image:
                radial-gradient(at 0% 0%, rgba(255, 121, 0, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(255, 121, 0, 0.03) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-body);
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .fw-bold {
            color: var(--text-heading);
            letter-spacing: -0.02em;
        }

        /* Modern Buttons */
        .btn {
            font-weight: 600;
            padding: 0.6rem 1.75rem;
            border-radius: var(--radius-sm);
            transition: var(--transition-smooth);
            letter-spacing: 0.01em;
        }

        .btn-orange {
            background: var(--orange-gradient);
            color: white;
            border: none;
            box-shadow: 0 4px 12px var(--orange-glow);
            position: relative;
            overflow: hidden;
        }

        .btn-orange::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(255, 255, 255, 0.2), transparent);
            opacity: 0;
            transition: var(--transition-smooth);
        }

        .btn-orange:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--orange-glow);
        }

        .btn-orange:hover::after {
            opacity: 1;
        }

        .btn-outline-orange {
            border: 2px solid var(--orange-primary);
            color: var(--orange-primary);
            background: transparent;
        }

        .btn-outline-orange:hover {
            background: var(--orange-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--orange-glow);
            border-color: var(--orange-primary);
        }

        /* Abstract Cards */
        .card {
            background: var(--orange-surface-solid);
            border: 1px solid var(--orange-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: var(--transition-smooth);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--orange-border);
            padding: 1.25rem 1.5rem;
        }

        /* Inputs & Forms */
        .form-control,
        .form-select {
            border-radius: var(--radius-sm);
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            background-color: #f8fafc;
            color: var(--text-heading);
            transition: var(--transition-smooth);
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: white;
            border-color: var(--orange-light);
            box-shadow: 0 0 0 4px rgba(255, 121, 0, 0.15);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-heading);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        /* Modern Tables */
        .table-responsive {
            border-radius: 0 0 var(--radius-md) var(--radius-md);
        }

        .table {
            margin-bottom: 0;
            color: var(--text-body);
        }

        .table>thead {
            background: #f8fafc;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
        }

        .table-hover tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* SaaS Badges */
        .badge {
            padding: 0.4em 0.8em;
            font-weight: 600;
            letter-spacing: 0.02em;
            border-radius: var(--radius-pill);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .bg-success {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #059669 !important;
        }

        .bg-warning {
            background: rgba(245, 158, 11, 0.15) !important;
            color: #d97706 !important;
        }

        .bg-danger {
            background: rgba(239, 68, 68, 0.15) !important;
            color: #dc2626 !important;
        }

        .bg-info {
            background: rgba(59, 130, 246, 0.15) !important;
            color: #2563eb !important;
        }

        .bg-primary {
            background: rgba(255, 121, 0, 0.15) !important;
            color: var(--orange-dark) !important;
        }

        .bg-dark {
            background: rgba(15, 23, 42, 0.1) !important;
            color: #334155 !important;
        }

        .bg-secondary {
            background: rgba(100, 116, 139, 0.1) !important;
            color: #475569 !important;
        }

        /* Pagination Links */
        .pagination {
            gap: 0.25rem;
        }

        .page-item .page-link {
            border: none;
            color: var(--text-body);
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.85rem;
            font-weight: 500;
            transition: var(--transition-smooth);
        }

        .page-item .page-link:hover {
            background: #f1f5f9;
            color: var(--orange-primary);
        }

        .page-item.active .page-link {
            background: var(--orange-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(255, 121, 0, 0.3);
        }

        /* Utilities */
        .text-orange {
            color: var(--orange-primary) !important;
        }

        a {
            color: var(--orange-primary);
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        a:hover {
            color: var(--orange-dark);
        }
    </style>
    @yield('styles')
</head>

<body>
    @yield('body')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/ar-dict.js') }}"></script>
    <script src="{{ asset('js/lang.js') }}"></script>
    @yield('scripts')
</body>

</html>