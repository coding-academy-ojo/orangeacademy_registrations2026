@extends('layouts.student')
@section('title', 'Student Dashboard')
@section('content')
    <div class="mb-4">
        <h3 class="fw-bold"><span data-en="Welcome," data-ar="مرحباً،">Welcome,</span> {{ $user->name }}!</h3>
        <p class="text-muted" data-en="Here's an overview of your registration status." data-ar="إليك نظرة عامة على حالة تسجيلك.">Here's an overview of your registration status.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary shadow-sm" style="width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-check-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Profile" data-ar="الملف الشخصي">Profile</div>
                        @if($user->profile && $user->profile->first_name_en)
                            <span class="badge bg-success" data-en="Complete" data-ar="مكتمل">Complete</span>
                        @else
                            <span class="badge bg-warning text-dark" data-en="Incomplete" data-ar="غير مكتمل">Incomplete</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success shadow-sm" style="width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-file-earmark-check-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Documents" data-ar="الوثائق">Documents</div>
                        <span class="fw-bold">
                            {{ $user->documents->count() }}
                            <span data-en="uploaded" data-ar="مرفوعة">uploaded</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-info shadow-sm" style="width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Assessments" data-ar="التقييمات">Assessments</div>
                        <span class="fw-bold">
                            {{ $user->assessmentSubmissions->count() }}
                            <span data-en="submitted" data-ar="مُرسَل">submitted</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enrollment Status --}}
    @forelse($user->enrollments as $enrollment)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $enrollment->cohort->name }}</h6>
                        <p class="text-muted small mb-0">{{ $enrollment->cohort->academy->name }} —
                            {{ $enrollment->cohort->academy->location }}</p>
                    </div>
                    <div class="text-end">
                        @php $statusColors = ['applied' => 'warning', 'accepted' => 'success', 'rejected' => 'danger', 'enrolled' => 'primary', 'graduated' => 'success', 'dropped' => 'secondary']; @endphp
                        <span
                            class="badge bg-{{ $statusColors[$enrollment->status] ?? 'secondary' }} {{ $enrollment->status == 'applied' ? 'text-dark' : '' }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                        <div class="small text-muted mt-1">{{ $enrollment->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-clipboard-plus fs-1 text-muted d-block mb-3"></i>
                <h6 data-en="No enrollment found" data-ar="لا يوجد تسجيل">No enrollment found</h6>
                <p class="text-muted" data-en="Start your registration to apply to a program." data-ar="ابدأ تسجيلك للتقدم لأحد البرامج.">Start your registration to apply to a program.</p>
                <a href="{{ route('student.step', 1) }}" class="btn btn-orange" data-en="Start Registration" data-ar="ابدأ التسجيل">Start Registration</a>
            </div>
        </div>
    @endforelse

    @if($user->enrollments->count())
        <div class="mt-3">
            <a href="{{ route('student.step', 1) }}" class="btn btn-outline-orange">
                <i class="bi bi-pencil me-2"></i>
                <span data-en="Edit Registration" data-ar="تعديل التسجيل">Edit Registration</span>
            </a>
        </div>
    @endif
@endsection