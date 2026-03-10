@extends('layouts.admin')
@section('title', 'User Details')
@section('page-title', 'User Details')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">User Details: {{ $user->email }}</h4>
            <a href="{{ route('admin.user-progress') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Registration Progress</h5>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $allSteps = [
                                'email_verified' => 'Email Verified',
                                'profile' => 'Personal Info',
                                'documents' => 'Documents',
                                'enrollment' => 'Cohort Selection',
                                'questionnaire' => 'Questionnaire',
                                'submitted' => 'Final Submission'
                            ];
                        @endphp

                        <div class="mb-3">
                            <div style="width: 120px; height: 120px; margin: 0 auto; position: relative;">
                                <svg viewBox="0 0 36 36" class="circular-chart">
                                    <path class="circle-bg"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="circle" stroke-dasharray="{{ $registrationProgress }}, 100"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <text x="18" y="20.35" class="percentage">{{ $registrationProgress }}%</text>
                                </svg>
                            </div>
                        </div>

                        @foreach($allSteps as $key => $label)
                            <div class="d-flex align-items-center mb-2">
                                <i
                                    class="bi {{ in_array($key, $completedSteps) ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} me-2"></i>
                                <span
                                    class="{{ in_array($key, $completedSteps) ? 'text-success' : 'text-muted' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">User Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Name:</strong> {{ $user->profile->first_name_en ?? '' }}
                            {{ $user->profile->last_name_en ?? '' }}</p>
                        <p><strong>Phone:</strong> {{ $user->profile->phone ?? '-' }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($user->profile->gender ?? '-') }}</p>
                        <p><strong>Registered:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>Email Verified:</strong>
                            {{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i') : 'No' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Activity Log</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($activities as $activity)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <span
                                                class="badge bg-{{ $activity->type_color }}">{{ $activity->type_label }}</span>
                                        </div>
                                        <div>
                                            <p class="mb-1"><strong>{{ $activity->title }}</strong></p>
                                            <small class="text-muted">{{ $activity->created_at->format('Y-m-d H:i:s') }}</small>
                                            @if($activity->description)
                                                <p class="mb-0 mt-1">{{ $activity->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No activities recorded</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Documents</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Status</th>
                                    <th>Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->documents as $doc)
                                    <tr>
                                        <td>{{ $doc->documentRequirement->name ?? 'Unknown' }}</td>
                                        <td>
                                            @if($doc->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $doc->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No documents uploaded</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Enrollment</h5>
                    </div>
                    <div class="card-body">
                        @if($user->enrollments->count() > 0)
                            @foreach($user->enrollments as $enrollment)
                                <p>
                                    <strong>Cohort:</strong> {{ $enrollment->cohort->name ?? '-' }}<br>
                                    <strong>Academy:</strong> {{ $enrollment->cohort->academy->name ?? '-' }}<br>
                                    <strong>Status:</strong>
                                    <span
                                        class="badge bg-{{ $enrollment->status == 'accepted' ? 'success' : ($enrollment->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </p>
                            @endforeach
                        @else
                            <p class="text-muted">No enrollment</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .circular-chart {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            max-height: 250px;
        }

        .circle-bg {
            fill: none;
            stroke: #eee;
            stroke-width: 2.5;
        }

        .circle {
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            animation: progress 1s ease-out forwards;
            stroke: #0d6efd;
        }

        .percentage {
            fill: #666;
            font-family: sans-serif;
            font-weight: bold;
            font-size: 0.5em;
            text-anchor: middle;
        }
    </style>
@endsection