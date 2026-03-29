@extends('layouts.admin')
@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Activity Logs</h4>
            <div>
                <a href="{{ route('admin.user-progress') }}" class="btn btn-info me-2">
                    <i class="bi bi-person-check"></i> User Progress
                </a>
                <a href="{{ route('admin.missed-data') }}" class="btn btn-warning">
                    <i class="bi bi-exclamation-triangle"></i> Missed Data
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($types as $key => $type)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Action</th>
                                <th>Title</th>
                                <th>User</th>
                                <th>IP Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                            
                                <tr>
                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $activity->type_color }}">
                                            {{ $activity->type_label }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->action }}</td>
                                    <td>{{ $activity->title }}</td>
                                    <td>
                                        @if($activity->user)
                                            <a href="{{ route('admin.user-progress.detail', $activity->user) }}">
                                                {{ $activity->user->email }}
                                            </a>
                                        @elseif($activity->admin)
                                            <span class="text-danger">{{ $activity->admin->name }} (Admin)</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>
                                        <a href="{{ route('admin.activities.show', $activity) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No activities found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection