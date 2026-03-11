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
                        @if($a->image)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/' . $a->image) }}" alt="Academy Image" class="img-fluid rounded"
                                    style="max-height: 120px; object-fit: cover;">
                            </div>
                        @else
                            <div class="mb-3 text-center bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 120px;">
                                <i class="bi bi-building fs-1 text-secondary"></i>
                            </div>
                        @endif
                        <h6 class="fw-bold mb-1">{{ $a->name }}</h6>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            @if($a->location_link)
                                <a href="{{ $a->location_link }}" target="_blank"
                                    class="text-decoration-none">{{ $a->location }}</a>
                            @else
                                {{ $a->location }}
                            @endif
                        </p>
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