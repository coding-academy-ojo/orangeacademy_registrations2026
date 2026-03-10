@extends('layouts.student')
@section('title', $assessment->title)
@section('content')
<div class="mb-4">
    <a href="{{ route('student.step', 4) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Assessments</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">{{ $assessment->title }}</h4>
                <p class="text-muted mb-0">{{ $assessment->description }}</p>
            </div>
            <div class="text-end">
                @php $tc = ['code'=>'primary','english'=>'info','iq'=>'warning']; @endphp
                <span class="badge bg-{{ $tc[$assessment->type] ?? 'dark' }} {{ $assessment->type=='iq'?'text-dark':'' }} mb-1">
                    {{ $assessment->type == 'iq' ? 'IQ Test' : ucfirst($assessment->type) }}
                </span>
                <div class="small text-muted">Max Score: {{ $assessment->max_score }}</div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('student.assessment.submit', $assessment) }}">
    @csrf
    @foreach($assessment->questions as $q)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <label class="form-label fw-bold mb-0">
                        <span class="text-orange me-2">Q{{ $loop->iteration }}.</span>{{ $q->question_text }}
                    </label>
                    <span class="badge bg-light text-dark">{{ $q->points }} pts</span>
                </div>

                @if($q->question_type === 'multiple_choice' && $q->options)
                    <div class="mt-2">
                        @foreach($q->options as $opt)
                            <label class="d-block mb-2">
                                <div class="form-check">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="form-check-input"
                                        {{ ($existingAnswers[$q->id]->answer_text ?? '') === $opt ? 'checked' : '' }}>
                                    <span class="form-check-label">{{ $opt }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @elseif($q->question_type === 'code')
                    <textarea name="answers[{{ $q->id }}]" class="form-control font-monospace" rows="8"
                        placeholder="Write your code here..." style="background:#1e1e1e;color:#d4d4d4;font-size:0.875rem;">{{ $existingAnswers[$q->id]->answer_text ?? '' }}</textarea>
                @else
                    <textarea name="answers[{{ $q->id }}]" class="form-control" rows="4"
                        placeholder="Type your answer here...">{{ $existingAnswers[$q->id]->answer_text ?? '' }}</textarea>
                @endif
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-between">
        <a href="{{ route('student.step', 4) }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-orange btn-lg px-5"
            onclick="return confirm('Are you sure? You cannot change your answers after submission.')">
            <i class="bi bi-send-fill me-2"></i>Submit Assessment
        </button>
    </div>
</form>
@endsection
