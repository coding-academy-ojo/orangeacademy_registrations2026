@extends('layouts.admin')
@section('page-title', isset($assessment) ? 'Edit Assessment' : 'Create Assessment')
@section('content')
    <form method="POST"
        action="{{ isset($assessment) ? route('admin.assessments.update', $assessment) : route('admin.assessments.store') }}">
        @csrf
        @if(isset($assessment)) @method('PUT') @endif

        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Assessment Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required
                            value="{{ old('title', $assessment->title ?? '') }}" placeholder="e.g. English Level Test">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            @foreach(['code' => 'Coding', 'english' => 'English', 'iq' => 'IQ / Aptitude'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type', $assessment->type ?? '') == $val ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Max Score <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="max_score" class="form-control" required
                            value="{{ old('max_score', $assessment->max_score ?? 100) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2"
                            placeholder="Instructions for students...">{{ old('description', $assessment->description ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" class="form-check-input" {{ old('is_published', $assessment->is_published ?? false) ? 'checked' : '' }}>
                            <span class="form-check-label fw-semibold">Publish immediately</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-ol text-orange me-2"></i>Questions</h6>
                <button type="button" class="btn btn-sm btn-orange" onclick="addQuestion()"><i
                        class="bi bi-plus-circle me-1"></i>Add Question</button>
            </div>
            <div class="card-body" id="questions-container">
                @if(isset($assessment) && $assessment->questions->count())
                    @foreach($assessment->questions as $i => $q)
                        <div class="question-block border rounded p-3 mb-3 position-relative" data-index="{{ $i }}">
                            <button type="button" class="btn btn-sm btn-outline-danger position-absolute" style="top:8px;right:8px;"
                                onclick="this.closest('.question-block').remove()"><i class="bi bi-x-lg"></i></button>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Question {{ $i + 1 }}</label>
                                    <textarea name="questions[{{ $i }}]" class="form-control" rows="2"
                                        required>{{ $q->question_text }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Type</label>
                                    <select name="question_types[{{ $i }}]" class="form-select form-select-sm"
                                        onchange="toggleOptions(this, {{ $i }})">
                                        <option value="fill_in" {{ $q->question_type == 'fill_in' ? 'selected' : '' }}>Fill In
                                        </option>
                                        <option value="multiple_choice" {{ $q->question_type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                        <option value="code" {{ $q->question_type == 'code' ? 'selected' : '' }}>Code</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Points</label>
                                    <input type="number" step="0.01" name="points[{{ $i }}]" class="form-control form-control-sm"
                                        value="{{ $q->points }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Correct Answer</label>
                                    <input type="text" name="correct_answers[{{ $i }}]" class="form-control form-control-sm"
                                        value="{{ $q->correct_answer }}" placeholder="Optional">
                                </div>
                                <div class="col-12 options-group" id="options-{{ $i }}"
                                    style="{{ $q->question_type !== 'multiple_choice' ? 'display:none;' : '' }}">
                                    <label class="form-label small">Choices (one per field)</label>
                                    <div class="row g-2">
                                        @for($j = 0; $j < 4; $j++)
                                            <div class="col-md-3">
                                                <input type="text" name="options[{{ $i }}][]" class="form-control form-control-sm"
                                                    placeholder="Choice {{ chr(65 + $j) }}" value="{{ $q->options[$j] ?? '' }}">
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-orange px-4">{{ isset($assessment) ? 'Update' : 'Create' }}
                Assessment</button>
            <a href="{{ route('admin.assessments') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>

    <script>
        let questionIndex = {{ isset($assessment) ? $assessment->questions->count() : 0 }};

        function addQuestion() {
            const i = questionIndex++;
            const html = `
        <div class="question-block border rounded p-3 mb-3 position-relative" data-index="${i}">
            <button type="button" class="btn btn-sm btn-outline-danger position-absolute" style="top:8px;right:8px;" onclick="this.closest('.question-block').remove()"><i class="bi bi-x-lg"></i></button>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small">Question ${i + 1}</label>
                    <textarea name="questions[${i}]" class="form-control" rows="2" required placeholder="Enter your question..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Type</label>
                    <select name="question_types[${i}]" class="form-select form-select-sm" onchange="toggleOptions(this, ${i})">
                        <option value="fill_in">Fill In</option>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="code">Code</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Points</label>
                    <input type="number" step="0.01" name="points[${i}]" class="form-control form-control-sm" value="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Correct Answer</label>
                    <input type="text" name="correct_answers[${i}]" class="form-control form-control-sm" placeholder="Optional">
                </div>
                <div class="col-12 options-group" id="options-${i}" style="display:none;">
                    <label class="form-label small">Choices (one per field)</label>
                    <div class="row g-2">
                        <div class="col-md-3"><input type="text" name="options[${i}][]" class="form-control form-control-sm" placeholder="Choice A"></div>
                        <div class="col-md-3"><input type="text" name="options[${i}][]" class="form-control form-control-sm" placeholder="Choice B"></div>
                        <div class="col-md-3"><input type="text" name="options[${i}][]" class="form-control form-control-sm" placeholder="Choice C"></div>
                        <div class="col-md-3"><input type="text" name="options[${i}][]" class="form-control form-control-sm" placeholder="Choice D"></div>
                    </div>
                </div>
            </div>
        </div>`;
            document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
        }

        function toggleOptions(select, index) {
            const el = document.getElementById('options-' + index);
            el.style.display = select.value === 'multiple_choice' ? '' : 'none';
        }
    </script>
@endsection