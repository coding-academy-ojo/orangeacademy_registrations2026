@extends('layouts.student')
@section('title', 'Academy Dashboard')

@section('styles')
<style>
    /* Academy Theme CSS */
    :root {
        --academy-orange: #ff7900;
        --academy-orange-glow: rgba(255, 121, 0, 0.4);
        --academy-dark: #0a0a0b;
        --academy-glass: rgba(255, 255, 255, 0.03);
        --academy-glass-border: rgba(255, 255, 255, 0.1);
    }

    /* Core Layout */
    .dashboard-hero {
        position: relative;
        background: url('{{ asset("images/dashboard/hero-bg.png") }}') no-repeat center center;
        background-size: cover;
        border-radius: 24px;
        padding: 4rem 3rem;
        color: white;
        overflow: hidden;
        margin-bottom: 2.5rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(10,10,11,0.9) 0%, rgba(10,10,11,0.4) 60%, transparent 100%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
    }

    .hero-badge {
        background: rgba(255, 121, 0, 0.2);
        border: 1px solid var(--academy-orange);
        color: var(--academy-orange);
        padding: 0.5rem 1.25rem;
        border-radius: 99px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 1.5rem;
        animation: fadeInDown 0.8s ease-out;
    }

    .welcome-text {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(to right, #fff, #ccc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: fadeInLeft 1s ease-out;
    }

    /* Interactive Cards */
    .academy-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        height: 100%;
        backdrop-filter: blur(10px);
    }

    .academy-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border-color: var(--academy-orange);
    }

    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .academy-card:hover .icon-box {
        transform: rotate(10deg) scale(1.1);
    }

    /* Animations */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    .float-anim {
        animation: float 4s ease-in-out infinite;
    }

    /* Enhanced Enrollment Box */
    .enrollment-panel {
        background: white;
        border-left: 5px solid var(--academy-orange);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        text-decoration: none !important;
        color: inherit !important;
    }

    .enrollment-panel:hover {
        background: #fafafa;
        transform: translateX(10px);
    }

    /* RTL Adjustments */
    body.lang-ar .enrollment-panel {
        border-left: none;
        border-right: 5px solid var(--academy-orange);
    }
    body.lang-ar .enrollment-panel:hover {
        transform: translateX(-10px);
    }

    /* Progress Visualizer */
    .academy-progress {
        height: 8px;
        background: #eee;
        border-radius: 99px;
        overflow: hidden;
        margin-top: 1rem;
    }
    .progress-bar-glow {
        background: var(--academy-orange-gradient);
        box-shadow: 0 0 15px var(--academy-orange-glow);
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="dashboard-hero">
        <div class="hero-content">
            <span class="hero-badge" data-en="Future Developer" data-ar="مطور المستقبل">Future Developer</span>
            <h1 class="welcome-text">
                <span data-en="Welcome Back," data-ar="مرحباً بك،">Welcome Back,</span><br>
                {{ $user->name }}!
            </h1>
            <p class="text-white-50 fs-5 mb-0" data-en="Your journey to mastery starts here. Keep pushing your limits." 
               data-ar="رحلتك نحو الإتقان تبدأ من هنا. استمر في تجاوز حدودك.">
                Your journey to mastery starts here. Keep pushing your limits.
            </p>
        </div>
        <img src="{{ asset('images/dashboard/learning-path.png') }}" 
             class="float-anim opacity-50 d-none d-lg-block" 
             style="position:absolute; right: 5%; top: 20%; width: 350px; pointer-events:none; z-index: 1;">
    </div>

    <!-- Alert for Rejections -->
    @if(isset($rejectedDocuments) && $rejectedDocuments->count() > 0)
        <div class="alert alert-danger border-0 shadow rounded-4 p-4 mb-5 animate__animated animate__shakeX">
            <div class="d-flex align-items-center gap-4">
                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 56px; height: 56px; border: 4px solid rgba(255,255,255,0.2);">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-1" data-en="Attention Required" data-ar="تنبيه هام">Attention Required</h5>
                    <p class="mb-3 opacity-75 small" data-en="Some documents need correction to maintain your eligibility." 
                       data-ar="بعض الوثائق تحتاج لتصحيح للحفاظ على أهليتك.">
                        Some documents need correction to maintain your eligibility.
                    </p>
                    <div class="bg-white bg-opacity-50 p-3 rounded-3 mb-3 border border-danger border-opacity-25">
                        <ul class="mb-0 small fw-medium">
                            @foreach($rejectedDocuments as $doc)
                                <li>
                                    <span class="text-dark">{{ $doc->documentRequirement->name }}:</span>
                                    <span class="text-danger ms-1">{{ $doc->rejection_reason }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ route('student.step', 2) }}" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-arrow-repeat me-2"></i><span data-en="Resolve Now" data-ar="حل الآن">Resolve Now</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Grid -->
    <div class="row g-4 mb-5">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="academy-card p-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-code-square"></i>
                </div>
                <h5 class="fw-bold mb-1" data-en="Profile Mastery" data-ar="إتقان الملف">Profile Mastery</h5>
                <p class="text-muted small mb-3" data-en="Personal & Academic Information" data-ar="المعلومات الشخصية والأكاديمية">
                    Personal & Academic Information
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    @if($user->profile && $user->profile->first_name_en)
                        <span class="badge bg-success" data-en="Optimized" data-ar="مُحسن">Optimized</span>
                        <span class="text-success small fw-bold">100%</span>
                    @else
                        <span class="badge bg-warning text-dark" data-en="Pending Build" data-ar="بانتظار البناء">Pending Build</span>
                        <span class="text-warning small fw-bold">0%</span>
                    @endif
                </div>
                <div class="academy-progress">
                    <div class="progress-bar-glow h-100" style="width: {{ $user->profile && $user->profile->first_name_en ? '100%' : '5%' }}; background: var(--academy-orange);"></div>
                </div>
            </div>
        </div>

        <!-- Documents Card -->
        <div class="col-md-4">
            <div class="academy-card p-4">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-layers"></i>
                </div>
                <h5 class="fw-bold mb-1" data-en="Asset Repository" data-ar="مستودع الأصول">Asset Repository</h5>
                <p class="text-muted small mb-3" data-en="Verified identity documents" data-ar="وثائق الهوية الموثقة">
                    Verified identity documents
                </p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold fs-4">{{ $user->documents->count() }} <span class="fs-6 text-muted fw-normal">/ {{ $totalRequiredDocuments }}</span></div>
                    <span class="badge bg-dark" data-en="Stored" data-ar="مخزن">Stored</span>
                </div>
                <div class="academy-progress">
                    <div class="progress-bar-glow h-100" style="width: {{ $totalRequiredDocuments > 0 ? ($user->documents->count() / $totalRequiredDocuments) * 100 : 0 }}%; background: #10b981;"></div>
                </div>
            </div>
        </div>

        <!-- Assessments Card -->
        <div class="col-md-4">
            <div class="academy-card p-4">
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="bi bi-terminal"></i>
                </div>
                <h5 class="fw-bold mb-1" data-en="Skill Validation" data-ar="تحقق من المهارات">Skill Validation</h5>
                <p class="text-muted small mb-3" data-en="Completed technical challenges" data-ar="التحديات التقنية المكتملة">
                    Completed technical challenges
                </p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold fs-4">{{ $user->assessmentSubmissions->count() }} <span class="fs-6 text-muted fw-normal">/ {{ $totalPublishedAssessments }}</span></div>
                    <span class="badge bg-info" data-en="Modules" data-ar="وحدات">Modules</span>
                </div>
                <div class="academy-progress">
                    <div class="progress-bar-glow h-100" style="width: {{ $totalPublishedAssessments > 0 ? ($user->assessmentSubmissions->count() / $totalPublishedAssessments) * 100 : 0 }}%; background: #3b82f6;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Status -->
    <h4 class="fw-bold mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-cpu text-orange"></i>
        <span data-en="Active Cohorts" data-ar="الأفواج النشطة">Active Cohorts</span>
    </h4>

    @forelse($user->enrollments as $enrollment)
        <a href="{{ route('student.step', 4) }}" class="enrollment-panel mb-4">
            <div class="d-flex align-items-center gap-4">
                <div class="bg-light rounded-4 p-3 d-none d-sm-block">
                    <img src="https://img.icons8.com/isometric/50/ffffff/database.png" style="filter: invert(1); width: 40px;">
                </div>
                <div>
                    <h5 class="fw-bold mb-1">{{ $enrollment->cohort->name }}</h5>
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-geo-alt"></i>
                        {{ $enrollment->cohort->academy->name }} — {{ $enrollment->cohort->academy->location }}
                    </div>
                </div>
            </div>
            <div class="text-end">
                @php 
                    $statusColors = ['applied' => 'warning', 'accepted' => 'success', 'rejected' => 'danger', 'enrolled' => 'primary', 'graduated' => 'success', 'dropped' => 'secondary']; 
                    $statusTextAr = ['applied' => 'تم التقديم', 'accepted' => 'مقبول', 'rejected' => 'مرفوض', 'enrolled' => 'ملتحق', 'graduated' => 'خريج', 'dropped' => 'منسحب'];
                @endphp
                <span class="badge bg-{{ $statusColors[$enrollment->status] ?? 'secondary' }} py-2 px-4 shadow-sm">
                    <span data-en="{{ ucfirst($enrollment->status) }}" data-ar="{{ $statusTextAr[$enrollment->status] ?? $enrollment->status }}">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </span>
                <div class="small text-muted mt-2">{{ $enrollment->created_at->format('M d, Y') }}</div>
            </div>
        </a>
    @empty
        <div class="card border-dashed">
            <div class="card-body text-center py-5">
                <div class="p-4 bg-light rounded-circle d-inline-block mb-4">
                    <i class="bi bi-laptop fs-1 text-muted"></i>
                </div>
                <h5 class="fw-bold mb-2" data-en="Begin Your Legacy" data-ar="ابدأ إرثك اليوم">Begin Your Legacy</h5>
                <p class="text-muted mx-auto mb-4" style="max-width: 400px;" 
                   data-en="No active programs detected. Initialize your enrollment to unlock the curriculum." 
                   data-ar="لم يتم العثور على برامج نشطة. ابدأ تسجيلك لفتح المنهج الدراسي.">
                    No active programs detected. Initialize your enrollment to unlock the curriculum.
                </p>
                <a href="{{ route('student.step', 1) }}" class="btn btn-orange btn-lg rounded-pill px-5 shadow-lg">
                    <span data-en="Start Build 1.0" data-ar="ابدأ البناء v1.0">Start Build 1.0</span>
                    <i class="bi bi-chevron-right ms-2 mt-1"></i>
                </a>
            </div>
        </div>
    @endforelse

    @if($user->enrollments->count())
        <div class="mt-4 text-center">
            <a href="{{ route('student.step', 1) }}" class="btn btn-link text-muted text-decoration-none hover-orange">
                <i class="bi bi-terminal-plus me-2"></i>
                <span data-en="Update Application Configuration" data-ar="تحديث إعدادات التقديم">Update Application Configuration</span>
            </a>
        </div>
    @endif
@endsection

@section('scripts')
<script>
    // Smooth entrance for cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.academy-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection