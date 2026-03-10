@extends('layouts.admin')
@section('page-title', 'Enrollments')
@section('content')
    <div class="card">
        <div class="card-header bg-white py-3">
            <form class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width:auto;">
                    <option value="">All Status</option>
                    @foreach(['applied', 'accepted', 'rejected', 'enrolled', 'graduated', 'dropped'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-orange">Filter</button>
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