@extends('layouts.student')
@section('title', 'Step 5 - Assessments')
@section('content')
    @include('student.registration._progress', ['step' => 5])
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-journal-check text-orange me-2"></i>Assessments</h5>
        </div>
        <div class="card-body p-4">
            <p class="text-muted mb-4">Complete the available assessments below. Each assessment may include multiple
                choice, fill-in, or coding questions.</p>

            @forelse($assessments as $assessment)
                <div class="card border mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $assessment->title }}</h6>
                                <p class="text-muted small mb-2">{{ $assessment->description }}</p>
                                <div class="d-flex gap-2 small">
                                    @php $tc = ['code' => 'primary', 'english' => 'info', 'iq' => 'warning']; @endphp
                                    <span
                                        class="badge bg-{{ $tc[$assessment->type] ?? 'dark' }} {{ $assessment->type == 'iq' ? 'text-dark' : '' }}">
                                        {{ $assessment->type == 'iq' ? 'IQ Test' : ucfirst($assessment->type) }}
                                    </span>
                                    <span class="text-muted"><i
                                            class="bi bi-question-circle me-1"></i>{{ $assessment->questions_count }}
                                        questions</span>
                                    <span class="text-muted"><i class="bi bi-star me-1"></i>{{ $assessment->max_score }}
                                        points</span>
                                </div>
                            </div>
                            <div>
                                @php $sub = $submissions[$assessment->id] ?? null; @endphp
                                @if(!$sub)
                                    <a href="{{ route('student.assessment.take', $assessment) }}" class="btn btn-sm btn-orange">
                                        <i class="bi bi-play-fill me-1"></i>Start
                                    </a>
                                @elseif($sub->status === 'in_progress')
                                    <a href="{{ route('student.assessment.take', $assessment) }}"
                                        class="btn btn-sm btn-outline-orange">
                                        <i class="bi bi-pencil me-1"></i>Continue
                                    </a>
                                @elseif($sub->status === 'submitted')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending Review</span>
                                @elseif($sub->status === 'graded')
                                    <span class="badge bg-success"><i
                                            class="bi bi-check-circle me-1"></i>{{ $sub->score }}/{{ $assessment->max_score }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                    <p>No assessments available yet.</p>
                </div>
            @endforelse

            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <a href="{{ route('student.step', 4) }}" class="btn btn-outline-secondary"><i
                        class="bi bi-arrow-left me-2"></i>Back</a>
                <a href="{{ route('student.step', 6) }}" class="btn btn-orange">Continue <i
                        class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>
@endsection