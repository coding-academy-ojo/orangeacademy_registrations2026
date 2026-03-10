@extends('layouts.admin')
@section('page-title', 'Assessments')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">All Assessments</h6>
        <a href="{{ route('admin.assessments.create') }}" class="btn btn-sm btn-orange"><i
                class="bi bi-plus-circle me-1"></i>Create Assessment</a>
    </div>

    @forelse($assessments as $assessment)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $assessment->title }}</h6>
                        <div class="d-flex flex-wrap gap-2 small">
                            @php $tc = ['code' => 'primary', 'english' => 'info', 'iq' => 'warning']; @endphp
                            <span
                                class="badge bg-{{ $tc[$assessment->type] ?? 'dark' }} {{ $assessment->type == 'iq' ? 'text-dark' : '' }}">
                                {{ $assessment->type == 'iq' ? 'IQ Test' : ucfirst($assessment->type) }}
                            </span>
                            <span class="text-muted"><i
                                    class="bi bi-question-circle me-1"></i>{{ $assessment->questions_count }} questions</span>
                            <span class="text-muted"><i class="bi bi-star me-1"></i>{{ $assessment->max_score }} pts</span>
                            <span class="text-muted"><i class="bi bi-people me-1"></i>{{ $assessment->submissions_count }}
                                submissions</span>
                            @if($assessment->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </div>
                        @if($assessment->description)
                            <p class="text-muted small mt-1 mb-0">{{ Str::limit($assessment->description, 100) }}</p>
                        @endif
                    </div>
                    <div class="d-flex gap-1">
                        <form method="POST" action="{{ route('admin.assessments.publish', $assessment) }}">
                            @csrf
                            <button class="btn btn-sm {{ $assessment->is_published ? 'btn-outline-secondary' : 'btn-success' }}"
                                title="{{ $assessment->is_published ? 'Unpublish' : 'Publish' }}">
                                <i class="bi {{ $assessment->is_published ? 'bi-eye-slash' : 'bi-send' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.assessments.submissions', $assessment) }}"
                            class="btn btn-sm btn-outline-orange" title="View Submissions">
                            <i class="bi bi-people"></i>
                        </a>
                        <a href="{{ route('admin.assessments.edit', $assessment) }}" class="btn btn-sm btn-outline-orange"
                            title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.assessments.destroy', $assessment) }}"
                            onsubmit="return confirm('Delete this assessment?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                <p>No assessments created yet.</p>
                <a href="{{ route('admin.assessments.create') }}" class="btn btn-orange">Create Your First Assessment</a>
            </div>
        </div>
    @endforelse

    @if($assessments->hasPages())
        <div class="mt-4">
            {{ $assessments->links() }}
        </div>
    @endif
@endsection