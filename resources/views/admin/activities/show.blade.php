@extends('layouts.admin')
@section('title', 'Activity Details')
@section('page-title', 'Activity Details')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Activity Details</h4>
            <a href="{{ route('admin.activities') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Type:</strong> <span
                                class="badge bg-{{ $activity->type_color }}">{{ $activity->type_label }}</span></p>
                        <p><strong>Action:</strong> {{ $activity->action }}</p>
                        <p><strong>Title:</strong> {{ $activity->title }}</p>
                        <p><strong>Description:</strong> {{ $activity->description ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>User:</strong>
                            @if($activity->user)
                                <a
                                    href="{{ route('admin.user-progress.detail', $activity->user) }}">{{ $activity->user->email }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                        <p><strong>Admin:</strong> {{ $activity->admin->name ?? 'N/A' }}</p>
                        <p><strong>IP Address:</strong> {{ $activity->ip_address ?? 'N/A' }}</p>
                        <p><strong>Date:</strong> {{ $activity->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                @if($activity->properties)
                    <hr>
                    <h5>Additional Data</h5>
                    <pre class="bg-light p-3">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                @endif
            </div>
        </div>
    </div>
@endsection