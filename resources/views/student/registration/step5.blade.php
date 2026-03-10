@extends('layouts.student')
@section('title', 'Step 5 - Questionnaire')
@section('content')
@include('student.registration._progress', ['step' => 5])
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-question-circle-fill text-orange me-2"></i>Questionnaire</h5>
    </div>
    <div class="card-body p-4">
        @if($questionnaire)
            <h6 class="fw-bold">{{ $questionnaire->title }}</h6>
            <p class="text-muted mb-4">{{ $questionnaire->description }}</p>
            <form method="POST" action="{{ route('student.save.questionnaire') }}">
                @csrf
                @foreach($questionnaire->questions->sortBy('order') as $question)
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            {{ $loop->iteration }}. {{ $question->question_text }}
                        </label>
                        @if($question->question_type === 'boolean')
                            <div class="d-flex gap-3">
                                <label class="form-check">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="Yes" class="form-check-input"
                                        {{ ($answers[$question->id]->answer_text ?? '') === 'Yes' ? 'checked' : '' }}>
                                    <span class="form-check-label">Yes</span>
                                </label>
                                <label class="form-check">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="No" class="form-check-input"
                                        {{ ($answers[$question->id]->answer_text ?? '') === 'No' ? 'checked' : '' }}>
                                    <span class="form-check-label">No</span>
                                </label>
                            </div>
                        @else
                            <textarea name="answers[{{ $question->id }}]" class="form-control" rows="3" placeholder="Your answer...">{{ $answers[$question->id]->answer_text ?? '' }}</textarea>
                        @endif
                    </div>
                @endforeach
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('student.step', 4) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
                    <button type="submit" class="btn btn-orange">Save & Continue <i class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </form>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clipboard-x fs-1 d-block mb-3"></i>
                <p>No questionnaire available at the moment.</p>
                <a href="{{ route('student.step', 6) }}" class="btn btn-orange">Skip to Review <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        @endif
    </div>
</div>
@endsection