@extends('layouts.admin')
@section('page-title', 'Grade Submission')
@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.assessments.submissions', $submission->assessment) }}"
            class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Submissions</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">{{ $submission->assessment->title }}</h5>
                    <div class="small text-muted">Student:
                        <strong>{{ $submission->user->profile->first_name_en ?? $submission->user->email }}
                            {{ $submission->user->profile->last_name_en ?? '' }}</strong></div>
                </div>
                <div class="text-end">
                    <div class="small text-muted">Submitted: {{ $submission->submitted_at?->format('M d, Y H:i') }}</div>
                    <div class="small text-muted">Max Score: {{ $submission->assessment->max_score }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.submissions.grade.save', $submission) }}">
        @csrf
        @foreach($submission->assessment->questions as $q)
            @php $answer = $submission->answers->firstWhere('question_id', $q->id); @endphp
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0"><span
                                class="text-orange me-2">Q{{ $loop->iteration }}.</span>{{ $q->question_text }}</h6>
                        <span class="badge bg-light text-dark">{{ $q->points }} pts</span>
                    </div>

                    @if($q->question_type === 'multiple_choice' && $q->options)
                        <div class="mb-2 small">
                            <strong>Options:</strong>
                            @foreach($q->options as $opt)
                                <span
                                    class="badge {{ ($answer->answer_text ?? '') === $opt ? 'bg-orange text-white' : 'bg-light text-dark' }} me-1">{{ $opt }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($q->correct_answer)
                        <div class="small text-success mb-2"><i class="bi bi-check-circle me-1"></i>Correct:
                            {{ $q->correct_answer }}</div>
                    @endif

                    <div class="p-3 rounded mb-3" style="background:var(--orange-grey-100);">
                        <label class="small text-muted d-block mb-1">Student's Answer:</label>
                        @if($q->question_type === 'code')
                            <pre class="mb-0 p-2 rounded"
                                style="background:#1e1e1e;color:#d4d4d4;font-size:0.85rem;overflow-x:auto;">{{ $answer->answer_text ?? 'No answer' }}</pre>
                        @else
                            <p class="mb-0">{{ $answer->answer_text ?? 'No answer' }}</p>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <label class="fw-semibold small mb-0">Points:</label>
                        <input type="number" step="0.01" min="0" max="{{ $q->points }}" name="points[{{ $answer->id ?? '' }}]"
                            value="{{ $answer->points_earned ?? 0 }}" class="form-control form-control-sm" style="width:80px;">
                        <span class="text-muted small">/ {{ $q->points }}</span>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-orange px-4"><i class="bi bi-check-circle me-2"></i>Save Grade</button>
            <a href="{{ route('admin.assessments.submissions', $submission->assessment) }}"
                class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection