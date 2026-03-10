@extends('layouts.admin')
@section('page-title', isset($questionnaire) ? 'Edit Questionnaire' : 'New Questionnaire')
@section('content')
    <div class="card" style="max-width:700px;">
        <div class="card-body p-4">
            <form method="POST"
                action="{{ isset($questionnaire) ? route('admin.questionnaires.update', $questionnaire) : route('admin.questionnaires.store') }}">
                @csrf
                @if(isset($questionnaire)) @method('PUT') @endif
                <div class="mb-3">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $questionnaire->title ?? '') }}"
                        class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control"
                        rows="2">{{ old('description', $questionnaire->description ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-check">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input" {{ old('is_published', $questionnaire->is_published ?? false) ? 'checked' : '' }}>
                        <span class="form-check-label">Published</span>
                    </label>
                </div>

                <h6 class="fw-bold mt-4 mb-3">Questions</h6>
                <div id="questions-list">
                    @if(isset($questionnaire) && $questionnaire->questions->count())
                        @foreach($questionnaire->questions as $i => $q)
                            <div class="card border mb-2 question-row">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="text-muted small fw-bold">{{ $i + 1 }}.</span>
                                        <input type="text" name="questions[]" value="{{ $q->question_text }}"
                                            class="form-control form-control-sm" placeholder="Question text" required>
                                        <select name="question_types[]" class="form-select form-select-sm" style="width:120px;">
                                            @foreach(['text', 'boolean', 'choice'] as $t)<option value="{{ $t }}" {{ $q->question_type == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>@endforeach
                                        </select>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="this.closest('.question-row').remove()"><i class="bi bi-x"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card border mb-2 question-row">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="text-muted small fw-bold">1.</span>
                                    <input type="text" name="questions[]" class="form-control form-control-sm"
                                        placeholder="Question text" required>
                                    <select name="question_types[]" class="form-select form-select-sm" style="width:120px;">
                                        <option value="text">Text</option>
                                        <option value="boolean">Boolean</option>
                                        <option value="choice">Choice</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.question-row').remove()"><i class="bi bi-x"></i></button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary mb-4" onclick="addQ()"><i
                        class="bi bi-plus me-1"></i>Add Question</button>

                <div class="d-flex gap-2 pt-3 border-top">
                    <button type="submit" class="btn btn-orange">{{ isset($questionnaire) ? 'Update' : 'Create' }}</button>
                    <a href="{{ route('admin.questionnaires') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    @section('scripts')
        <script>
            function addQ() {
                const n = document.querySelectorAll('.question-row').length + 1;
                document.getElementById('questions-list').insertAdjacentHTML('beforeend',
                    `<div class="card border mb-2 question-row"><div class="card-body py-2 px-3"><div class="d-flex gap-2 align-items-center"><span class="text-muted small fw-bold">${n}.</span><input type="text" name="questions[]" class="form-control form-control-sm" placeholder="Question text" required><select name="question_types[]" class="form-select form-select-sm" style="width:120px;"><option value="text">Text</option><option value="boolean">Boolean</option><option value="choice">Choice</option></select><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.question-row').remove()"><i class="bi bi-x"></i></button></div></div></div>`
                );
            }
        </script>
    @endsection
@endsection