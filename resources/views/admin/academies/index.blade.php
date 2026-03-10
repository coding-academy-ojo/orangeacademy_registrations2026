@extends('layouts.admin')
@section('page-title', 'Academies')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">All Academies</h6>
        <a href="{{ route('admin.academies.create') }}" class="btn btn-sm btn-orange"><i
                class="bi bi-plus-circle me-1"></i>New Academy</a>
    </div>
    <div class="row g-3">
        @forelse($academies as $a)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">{{ $a->name }}</h6>
                        <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $a->location }}</p>
                        @if($a->code)<span class="badge bg-dark mb-2">{{ $a->code }}</span>@endif
                        <p class="small text-muted mb-0">{{ $a->cohorts_count }} cohort(s)</p>
                    </div>
                    <div class="card-footer bg-white border-top d-flex gap-2">
                        <a href="{{ route('admin.academies.edit', $a) }}" class="btn btn-sm btn-outline-orange"><i
                                class="bi bi-pencil"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.academies.destroy', $a) }}"
                            onsubmit="return confirm('Delete this academy?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5 text-muted">No academies yet</div>
                </div>
            </div>
        @endforelse

        @if($academies->hasPages())
            <div class="mt-4">
                {{ $academies->links() }}
            </div>
        @endif
    </div>
@endsection