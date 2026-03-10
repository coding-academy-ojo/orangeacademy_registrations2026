@extends('layouts.admin')
@section('page-title', 'Questionnaires')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">All Questionnaires</h6>
        <a href="{{ route('admin.questionnaires.create') }}" class="btn btn-sm btn-orange"><i
                class="bi bi-plus-circle me-1"></i>New</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questionnaires as $q)
                            <tr>
                                <td class="fw-semibold">{{ $q->title }}</td>
                                <td><span class="badge bg-dark">{{ $q->questions_count }}</span></td>
                                <td>@if($q->is_published)<span class="badge bg-success">Yes</span>@else<span
                                class="badge bg-secondary">No</span>@endif</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.questionnaires.answers', $q) }}"
                                            class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat-dots"></i></a>
                                        <a href="{{ route('admin.questionnaires.edit', $q) }}"
                                            class="btn btn-sm btn-outline-orange"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" action="{{ route('admin.questionnaires.destroy', $q) }}"
                                            onsubmit="return confirm('Delete?');">@csrf @method('DELETE')<button
                                                class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No questionnaires</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($questionnaires->hasPages())
        <div class="mt-4">
            {{ $questionnaires->links() }}
        </div>
    @endif
@endsection