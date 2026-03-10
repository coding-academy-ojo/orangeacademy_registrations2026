@extends('layouts.admin')
@section('page-title', 'Cohorts')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">All Cohorts</h6>
        <a href="{{ route('admin.cohorts.create') }}" class="btn btn-sm btn-orange"><i
                class="bi bi-plus-circle me-1"></i>New Cohort</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Academy</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Enrollments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cohorts as $c)
                            <tr>
                                <td class="fw-semibold">{{ $c->name }}</td>
                                <td class="small text-muted">{{ $c->academy->name }}</td>
                                <td class="small">{{ $c->start_date->format('M d') }} - {{ $c->end_date->format('M d, Y') }}
                                </td>
                                <td>
                                    @php $sc = ['planning' => 'secondary', 'active' => 'success', 'completed' => 'primary', 'cancelled' => 'danger']; @endphp
                                    <span class="badge bg-{{ $sc[$c->status] ?? 'secondary' }}">{{ ucfirst($c->status) }}</span>
                                </td>
                                <td><span class="badge bg-dark">{{ $c->enrollments_count }}</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.cohorts.edit', $c) }}" class="btn btn-sm btn-outline-orange"><i
                                                class="bi bi-pencil"></i></a>
                                        <form method="POST" action="{{ route('admin.cohorts.destroy', $c) }}"
                                            onsubmit="return confirm('Delete?');">@csrf @method('DELETE')<button
                                                class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No cohorts yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($cohorts->hasPages())
        <div class="mt-4">
            {{ $cohorts->links() }}
        </div>
    @endif
@endsection