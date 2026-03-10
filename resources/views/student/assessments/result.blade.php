@extends('layouts.student')
@section('title', 'Assessment Result')
@section('content')
    <div class="mb-4">
        <a href="{{ route('student.step', 4) }}" class="btn btn-sm btn-outline-secondary"><i
                class="bi bi-arrow-left me-1"></i>Back to Assessments</a>
    </div>

    <div class="card">
        <div class="card-body text-center py-5">
            @if($submission->status === 'graded')
                <div class="mb-3"
                    style="width:80px;height:80px;border-radius:50%;background:rgba(50,200,50,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
                </div>
                <h4 class="fw-bold mb-2">Assessment Graded</h4>
                <h2 class="text-orange fw-bold">{{ $submission->score }} / {{ $assessment->max_score }}</h2>
                <p class="text-muted">{{ $assessment->title }}</p>
            @else
                <div class="mb-3"
                    style="width:80px;height:80px;border-radius:50%;background:rgba(255,200,0,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <i class="bi bi-clock-fill" style="font-size:2.5rem;color:var(--orange-warning);"></i>
                </div>
                <h4 class="fw-bold mb-2">Assessment Submitted</h4>
                <p class="text-muted">Your answers for <strong>{{ $assessment->title }}</strong> are being reviewed by an admin.
                </p>
                <p class="small text-muted">Submitted {{ $submission->submitted_at->diffForHumans() }}</p>
            @endif
        </div>
    </div>
@endsection