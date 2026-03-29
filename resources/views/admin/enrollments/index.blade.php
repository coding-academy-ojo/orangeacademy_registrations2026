@extends('layouts.admin')
@section('page-title', 'Enrollments')
@section('content')
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white py-3 border-0">
            <div class="mb-3">
                <h6 class="fw-bold mb-3"><i class="bi bi-filter-circle me-2 text-orange"></i>Quick Academy Filters</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.enrollments') }}" class="btn btn-sm {{ !request('academy_id') ? 'btn-orange' : 'btn-outline-secondary' }}" style="border-radius: 8px;">All</a>
                    @foreach($academies->take(4) as $academy)
                        <a href="{{ route('admin.enrollments', array_merge(request()->query(), ['academy_id' => $academy->id])) }}" 
                           class="btn btn-sm {{ request('academy_id') == $academy->id ? 'btn-orange' : 'btn-outline-secondary' }}"
                           style="border-radius: 8px;">
                           {{ $academy->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <form class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search student or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="academy_id" class="form-select form-select-sm">
                        <option value="">All Academies</option>
                        @foreach($academies as $a)
                            <option value="{{ $a->id }}" {{ request('academy_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        @foreach(['applied', 'accepted', 'rejected', 'enrolled', 'graduated', 'dropped'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-sm btn-orange px-4">Filter</button>
                    @if(request()->anyFilled(['search', 'academy_id', 'status']))
                        <a href="{{ route('admin.enrollments') }}" class="btn btn-sm btn-link text-muted">Clear</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Email</th>
                            <th>Cohort</th>
                            <th>Academy</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $e)
                            <tr>
                                <td class="fw-semibold">{{ $e->user->profile->first_name_en ?? '-' }}
                                    {{ $e->user->profile->last_name_en ?? '' }}</td>
                                <td class="small">{{ $e->user->email }}</td>
                                <td class="small">{{ Str::limit($e->cohort->name, 25) }}</td>
                                <td class="small text-muted">{{ $e->cohort->academy->name }}</td>
                                <td>
                                    @php $c = ['applied' => 'warning', 'accepted' => 'success', 'rejected' => 'danger', 'enrolled' => 'primary', 'graduated' => 'success', 'dropped' => 'secondary']; @endphp
                                    <span
                                        class="badge bg-{{ $c[$e->status] ?? 'secondary' }} {{ $e->status == 'applied' ? 'text-dark' : '' }}">{{ ucfirst($e->status) }}</span>
                                </td>
                                <td class="small text-muted">{{ $e->created_at->format('M d') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.enrollments.status', $e) }}"
                                        class="d-flex gap-1">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm"
                                            style="width:auto;font-size:0.75rem;">
                                            @foreach(['applied', 'accepted', 'rejected', 'enrolled', 'graduated', 'dropped'] as $s)
                                                <option value="{{ $s }}" {{ $e->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-orange">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No enrollments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($enrollments->hasPages())
        <div class="card-footer bg-white">{{ $enrollments->withQueryString()->links() }}</div>@endif
    </div>
@endsection