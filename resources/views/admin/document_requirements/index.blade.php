@extends('layouts.admin')
@section('page-title', 'Manage Document Requirements')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Document Requirements</h2>
        <a href="{{ route('admin.document_requirements.create') }}" class="btn btn-orange">
            <i class="bi bi-plus-circle me-1"></i> Add Requirement
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Required?</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requirements as $req)
                            <tr>
                                <td class="fw-semibold">{{ $req->name }}</td>
                                <td class="text-muted small">{{ Str::limit($req->description, 50) }}</td>
                                <td>
                                    @if($req->is_required)
                                        <span class="badge bg-danger">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.document_requirements.edit', $req) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.document_requirements.destroy', $req) }}"
                                            onsubmit="return confirm('Delete this requirement? Students will no longer see this in their upload list.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No document requirements found. Add some to
                                    ask students for files!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection