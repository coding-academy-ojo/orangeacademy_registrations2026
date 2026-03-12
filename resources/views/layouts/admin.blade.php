@extends('layouts.base')

@section('styles')
    <style>
        .admin-sidebar {
            width: 280px;
            height: 100vh;
            background: #0f172a;
            background-image: linear-gradient(180deg, rgba(255, 121, 0, 0.03) 0%, rgba(0, 0, 0, 0) 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-content {
            flex-grow: 1;
            overflow-y: auto;
            padding-bottom: 2rem;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-content::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: var(--orange-primary);
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.5rem;
            position: sticky;
            top: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            z-index: 10;
        }

        .admin-sidebar .sidebar-brand .logo-box {
            width: 40px;
            height: 40px;
            background: var(--orange-gradient);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px var(--orange-glow);
        }

        .admin-sidebar .sidebar-brand .logo-line {
            width: 20px;
            height: 3px;
            background: white;
            border-radius: 2px;
        }

        .admin-sidebar .nav-section {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            font-weight: 700;
        }

        .admin-sidebar .nav {
            padding: 0 0.75rem;
        }

        .admin-sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            border-radius: var(--radius-sm);
            transition: var(--transition-smooth);
        }

        .admin-sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        .admin-sidebar .nav-link.active {
            color: white !important;
            background: var(--orange-gradient);
            box-shadow: 0 4px 12px var(--orange-glow);
            font-weight: 600;
        }

        .admin-sidebar .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            opacity: 0.8;
            transition: var(--transition-smooth);
        }

        .admin-sidebar .nav-link.active i {
            opacity: 1;
            transform: scale(1.1);
        }

        .admin-main {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--orange-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .admin-content {
            padding: 2rem;
            flex-grow: 1;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
                box-shadow: 20px 0 50px rgba(0, 0, 0, 0.5);
            }

            .admin-main {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 1040;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .admin-topbar {
                padding: 0.75rem 1rem;
            }

            .admin-topbar h5 {
                font-size: 1rem;
            }

            .admin-content {
                padding: 1rem;
            }

            /* Hide email on mobile to save space */
            .admin-topbar .text-muted.small {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .admin-content {
                padding: 0.75rem;
            }

            .admin-topbar {
                padding: 0.5rem 0.75rem;
            }

            .admin-topbar h5 {
                font-size: 0.9rem;
            }
        }

        /* RTL admin sidebar */
        body.lang-ar .admin-sidebar .nav-link:hover {
            transform: translateX(-4px);
        }
    </style>
@endsection

@section('scripts')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');

            // Prevent body scroll when sidebar is open on mobile
            if (sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    </script>
@endsection

@section('body')
    {{-- Sidebar --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="logo-box">
                    <div class="logo-line"></div>
                </div>
                <span class="text-white fw-bold" data-en="Admin Panel" data-ar="لوحة التحكم">Admin Panel</span>
            </div>
            <button class="btn btn-link text-white d-lg-none p-0" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-content">
            <div class="nav-section mt-3" data-en="Main" data-ar="الرئيسية">Main</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span data-en="Dashboard" data-ar="الرئيسية">Dashboard</span>
                </a>
            </nav>

            <div class="nav-section mt-3" data-en="Management" data-ar="الإدارة">Management</div>
            <nav class="nav flex-column">
                @can('manage-admins')
                    <a class="nav-link {{ request()->is('admin/admins*') ? 'active' : '' }}"
                        href="{{ route('admin.admins.index') }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span data-en="Admins" data-ar="المشرفون">Admins</span>
                    </a>
                @endcan
                @can('manage-operations')
                    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                        <i class="bi bi-people-fill"></i>
                        <span data-en="Students" data-ar="الطلاب">Students</span>
                    </a>
                    <a class="nav-link {{ request()->is('admin/enrollments*') ? 'active' : '' }}"
                        href="{{ route('admin.enrollments') }}">
                        <i class="bi bi-clipboard-check-fill"></i>
                        <span data-en="Enrollments" data-ar="التسجيلات">Enrollments</span>
                    </a>
                    <a class="nav-link {{ request()->is('admin/documents*') && !request()->is('admin/document_requirements*') ? 'active' : '' }}"
                        href="{{ route('admin.documents') }}">
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span data-en="Documents" data-ar="الوثائق">Documents</span>
                    </a>
                    <a class="nav-link {{ request()->is('admin/document_requirements*') ? 'active' : '' }}"
                        href="{{ route('admin.document_requirements.index') }}">
                        <i class="bi bi-file-earmark-ruled"></i>
                        <span data-en="Doc Requirements" data-ar="متطلبات الوثائق">Doc Requirements</span>
                    </a>
                @endcan
                @can('manage-interviews-assessments')
                    <a class="nav-link {{ request()->is('admin/assessments*') ? 'active' : '' }}"
                        href="{{ route('admin.assessments') }}">
                        <i class="bi bi-journal-check"></i>
                        <span data-en="Assessments" data-ar="التقييمات">Assessments</span>
                    </a>
                    <a class="nav-link {{ request()->is('admin/interviews*') ? 'active' : '' }}"
                        href="{{ route('admin.interviews.index') }}">
                        <i class="bi bi-person-video2" style="color:#ff7900"></i>
                        <span data-en="Academy Interviews" data-ar="مقابلات الأكاديمية">Academy Interviews</span>
                    </a>
                @endcan
            </nav>

            <div class="nav-section mt-3" data-en="Configuration" data-ar="الإعدادات">Configuration</div>
            <nav class="nav flex-column">
                @can('manage-operations')
                    <a class="nav-link {{ request()->is('admin/academies*') ? 'active' : '' }}"
                        href="{{ route('admin.academies') }}">
                        <i class="bi bi-building"></i>
                        <span data-en="Academies" data-ar="الأكاديميات">Academies</span>
                    </a>
                    <a class="nav-link {{ request()->is('admin/cohorts*') ? 'active' : '' }}"
                        href="{{ route('admin.cohorts') }}">
                        <i class="bi bi-collection-fill"></i>
                        <span data-en="Cohorts" data-ar="الدفعات">Cohorts</span>
                    </a>
                @endcan
                @can('manage-interviews-assessments')
                    <a class="nav-link {{ request()->is('admin/questionnaires*') ? 'active' : '' }}"
                        href="{{ route('admin.questionnaires') }}">
                        <i class="bi bi-question-circle-fill"></i>
                        <span data-en="Questionnaires" data-ar="الاستبيانات">Questionnaires</span>
                    </a>
                @endcan
            </nav>

            @can('manage-admins')
                <div class="nav-section mt-3" data-en="Activity Monitoring" data-ar="مراقبة النشاط">Activity Monitoring</div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->is('admin/activities*') ? 'active' : '' }}"
                        href="{{ route('admin.activities') }}">
                        <i class="bi bi-activity"></i>
                        <span data-en="Activity Logs" data-ar="سجل النشاط">Activity Logs</span>
                    </a>
                    <a class="nav-link {{ request()->is('admin/user-progress*') ? 'active' : '' }}"
                        href="{{ route('admin.user-progress') }}">
                        <i class="bi bi-person-lines-fill"></i>
                        <span data-en="User Progress" data-ar="تقدم المستخدمين">User Progress</span>
                    </a>
                    <a class="nav-link {{ request()->is('admin/missed-data*') ? 'active' : '' }}"
                        href="{{ route('admin.missed-data') }}">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span data-en="Missed Data" data-ar="بيانات ناقصة">Missed Data</span>
                    </a>
                </nav>
            @endcan
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Main Content Area --}}
    <div class="admin-main">
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                {{-- Language Toggle --}}
                <div class="lang-toggle">
                    <button class="lang-btn" data-lang-btn="en">EN</button>
                    <div class="lang-divider"></div>
                    <button class="lang-btn" data-lang-btn="ar">AR</button>
                </div>
                <span class="text-muted small">{{ auth('admin')->user()->email }}</span>
                <span class="badge rounded-pill"
                    style="background:var(--orange-primary);color:white;">{{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}</span>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
@endsection