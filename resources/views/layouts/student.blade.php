@extends('layouts.base')

@section('body')
    {{-- Student Navbar --}}
    <nav class="navbar navbar-expand-lg sticky-top border-bottom student-navbar"
        style="background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-color: var(--orange-border) !important;">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center gap-3" href="/">
                <div class="shadow-sm"
                    style="width:40px;height:40px;background:var(--orange-gradient);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;">
                    <div style="width:20px;height:3px;background:white;border-radius:2px;"></div>
                </div>
                <span class="fw-bold fs-5 text-dark" style="letter-spacing:-0.5px;" data-en="Orange Academy"
                    data-ar="أكاديمية أورنج">Orange Academy</span>
            </a>
            <div class="d-flex align-items-center gap-3">
                {{-- Language Toggle --}}
                <div class="lang-toggle light">
                    <button class="lang-btn" data-lang-btn="en">EN</button>
                    <div class="lang-divider"></div>
                    <button class="lang-btn" data-lang-btn="ar">AR</button>
                </div>
                @auth
                    <span class="text-muted small fw-medium">{{ auth()->user()->email }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-orange rounded-pill px-4 fw-bold" data-en="Logout"
                            data-ar="تسجيل الخروج">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
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

    {{-- Footer --}}
    <footer class="mt-5 py-4" style="background: var(--orange-grey-800); color: var(--orange-grey-400);">
        <div class="container text-center">
            <p class="mb-0 small" data-en="© {{ date('Y') }} Orange Jordan — Orange Academy Registration Portal"
                data-ar="© {{ date('Y') }} أورنج الأردن — بوابة تسجيل أكاديمية أورنج">
                &copy; {{ date('Y') }} Orange Jordan — Orange Academy Registration Portal
            </p>
        </div>
    </footer>
@endsection