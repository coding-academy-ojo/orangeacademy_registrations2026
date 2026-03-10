@extends('layouts.admin')
@section('page-title', 'Documents')
@section('content')
    <div class="card">
        <div class="card-header bg-white py-3">
            <form class="d-flex gap-2">
                <select name="verified" class="form-select form-select-sm" style="width:auto;">
                    <option value="">All</option>
                    <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
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
                            <th>Type</th>
                            <th>Verified</th>
                            <th>Uploaded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $d)
                            <tr>
                                <td>{{ $d->user->profile->first_name_en ?? $d->user->email }}
                                    {{ $d->user->profile->last_name_en ?? '' }}</td>
                                <td><span class="badge bg-dark">{{ str_replace('_', ' ', ucfirst($d->type)) }}</span></td>
                                <td>
                                    @if($d->is_verified)<span class="badge bg-success">Verified</span>@else<span
                                    class="badge bg-warning text-dark">Pending</span>@endif
                                </td>
                                <td class="small text-muted">{{ $d->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ asset('storage/' . $d->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                        @if(!$d->is_verified)
                                            <form method="POST" action="{{ route('admin.documents.verify', $d) }}">@csrf<button
                                                    class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Verify</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.documents.unverify', $d) }}">@csrf<button
                                                    class="btn btn-sm btn-outline-warning"><i class="bi bi-x"></i></button></form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No documents found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($documents->hasPages())
        <div class="card-footer bg-white">{{ $documents->withQueryString()->links() }}</div>@endif
    </div>
@endsection