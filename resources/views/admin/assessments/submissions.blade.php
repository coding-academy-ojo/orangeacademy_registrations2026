@extends('layouts.admin')
@section('page-title', 'Submissions: ' . $assessment->title)
@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.assessments') }}" class="btn btn-sm btn-outline-secondary"><i
                class="bi bi-arrow-left me-1"></i>Back to Assessments</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">{{ $assessment->title }}</h5>
                    <div class="small text-muted">{{ $assessment->questions->count() }} questions ·
                        {{ $assessment->max_score }} max pts
                    </div>
                </div>
                @php $tc = ['code' => 'primary', 'english' => 'info', 'iq' => 'warning']; @endphp
                <span
                    class="badge bg-{{ $tc[$assessment->type] ?? 'dark' }} {{ $assessment->type == 'iq' ? 'text-dark' : '' }}">{{ $assessment->type == 'iq' ? 'IQ' : ucfirst($assessment->type) }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $sub)
                            <tr>
                                <td class="fw-semibold">{{ $sub->user->profile->first_name_en ?? $sub->user->email }}
                                    {{ $sub->user->profile->last_name_en ?? '' }}
                                </td>
                                <td>
                                    @php $sc = ['in_progress' => 'secondary', 'submitted' => 'warning', 'graded' => 'success']; @endphp
                                    <span
                                        class="badge bg-{{ $sc[$sub->status] ?? 'dark' }} {{ $sub->status == 'submitted' ? 'text-dark' : '' }}">
                                        {{ $sub->status == 'in_progress' ? 'In Progress' : ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($sub->status === 'graded')
                                        <strong>{{ $sub->score }}</strong><span
                                            class="text-muted">/{{ $assessment->max_score }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $sub->submitted_at?->format('M d, Y H:i') ?? '—' }}</td>
                                <td>
                                    @if($sub->status !== 'in_progress')
                                        <a href="{{ route('admin.submissions.grade', $sub) }}"
                                            class="btn btn-sm btn-outline-orange">
                                            <i
                                                class="bi bi-pencil-square me-1"></i>{{ $sub->status === 'graded' ? 'Re-grade' : 'Grade' }}
                                        </a>
                                    @else
                                        <span class="text-muted small">Not submitted</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No submissions yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($submissions->hasPages())
                <div class="card-footer bg-white pb-0 border-top-0">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection