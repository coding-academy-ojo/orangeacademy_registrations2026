@extends('layouts.admin')
@section('title', 'Missed Data')
@section('page-title', 'Incomplete Registrations')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Users with Incomplete Registration</h4>
            <div>
                <a href="{{ route('admin.activities') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-clock-history"></i> Activity Logs
                </a>
                <a href="{{ route('admin.user-progress') }}" class="btn btn-info">
                    <i class="bi bi-person-check"></i> User Progress
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Not Started</h6>
                                <h3 class="mb-0">{{ $users->filter(fn($u) => $u->registration_progress < 20)->count() }}
                                </h3>
                            </div>
                            <i class="bi bi-x-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">In Progress</h6>
                                <h3 class="mb-0">
                                    {{ $users->filter(fn($u) => $u->registration_progress >= 20 && $u->registration_progress < 100)->count() }}
                                </h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Completed</h6>
                                <h3 class="mb-0">{{ $users->filter(fn($u) => $u->registration_progress == 100)->count() }}
                                </h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Total Users</h6>
                                <h3 class="mb-0">{{ $users->count() }}</h3>
                            </div>
                            <i class="bi bi-people fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Registered</th>
                                <th>Progress</th>
                                <th>Missing</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $allSteps = ['email_verified', 'profile', 'documents', 'enrollment', 'questionnaire', 'submitted'];
                                    $completed = $user->completed_steps;
                                    $missing = array_diff($allSteps, $completed);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-circle bg-{{ $user->registration_progress == 100 ? 'success' : 'warning' }} me-2">
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
                                        @php $progressClass = $user->registration_progress >= 100 ? 'success' : 'warning'; @endphp
                                        <div class="progress" style="height: 20px; width: 100px;">
                                            <div class="progress-bar bg-{{ $progressClass }}"
                                                style="width: {{ $user->registration_progress }}%">
                                                {{ $user->registration_progress }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($missing as $m)
                                            <span class="badge bg-danger me-1">{{ ucfirst(str_replace('_', ' ', $m)) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->updated_at)
                                            {{ $user->updated_at->format('Y-m-d H:i') }}
                                        @else
                                            -
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
                                    <td colspan="6" class="text-center py-4">All users have completed their registration!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    </style>
@endsection