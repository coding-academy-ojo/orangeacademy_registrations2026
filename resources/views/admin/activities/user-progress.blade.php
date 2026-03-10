@extends('layouts.admin')
@section('title', 'User Progress')
@section('page-title', 'User Registration Progress')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">User Registration Progress</h4>
            <div>
                <a href="{{ route('admin.activities') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-clock-history"></i> Activity Logs
                </a>
                <a href="{{ route('admin.missed-data') }}" class="btn btn-warning">
                    <i class="bi bi-exclamation-triangle"></i> Missed Data
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Registered Date</th>
                                <th>Progress</th>
                                <th>Completed Steps</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-circle bg-{{ $user->registration_progress == 100 ? 'success' : 'primary' }} me-2">
                                                {{ strtoupper(substr($user->email, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $user->email }}</div>
                                                <small class="text-muted">{{ $user->profile->first_name_en ?? '' }}
                                                    {{ $user->profile->last_name_en ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @php
                                            $progressClass = $user->registration_progress >= 100 ? 'success' : ($user->registration_progress >= 50 ? 'warning' : 'danger');
                                        @endphp
                                        <div class="progress" style="height: 20px; width: 150px;">
                                            <div class="progress-bar bg-{{ $progressClass }}" role="progressbar"
                                                style="width: {{ $user->registration_progress }}%">
                                                {{ $user->registration_progress }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($user->completed_steps as $step)
                                            <span class="badge bg-success me-1">{{ ucfirst(str_replace('_', ' ', $step)) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->registration_progress == 100)
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($user->registration_progress >= 50)
                                            <span class="badge bg-warning">In Progress</span>
                                        @else
                                            <span class="badge bg-danger">Not Started</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.user-progress.detail', $user) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $users->links() }}
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .bg-primary {
            background-color: #0d6efd !important;
        }
    </style>
@endsection