@extends('layouts.admin')
@section('page-title', 'Questionnaire Answers')
@section('content')
    <a href="{{ route('admin.questionnaires') }}" class="btn btn-sm btn-outline-secondary mb-3"><i
            class="bi bi-arrow-left me-1"></i>Back</a>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="fw-bold mb-1">{{ $questionnaire->title }}</h5>
            <p class="text-muted small mb-0">{{ $questionnaire->description }}</p>
        </div>
    </div>

    @foreach($questionnaire->questions as $question)
        <div class="card mb-3">
            <div class="card-header bg-white py-2">
                <strong class="small">Q{{ $question->order }}: {{ $question->question_text }}</strong>
                <span class="badge bg-secondary ms-2">{{ $question->answers->count() }} answers</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        @foreach($question->answers as $answer)
                            <tr>
                                <td class="small fw-semibold" style="width:200px;">
                                    {{ $answer->user->profile->first_name_en ?? $answer->user->email }}</td>
                                <td class="small">{{ $answer->answer_text ?? '-' }}</td>
                            </tr>
                        @endforeach
                        @if($question->answers->isEmpty())
                            <tr>
                                <td class="text-center text-muted py-2 small">No answers yet</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection