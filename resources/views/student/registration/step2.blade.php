@extends('layouts.student')
@section('title', 'Step 2 - Documents')
@section('content')
    @include('student.registration._progress', ['step' => 2])
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-arrow-up-fill text-orange me-2"></i>Upload Documents</h5>
        </div>
        <div class="card-body p-4">
            @if($documents->count())
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Your Uploaded Documents</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Status</th>
                                    <th>Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    <tr>
                                        <td>
                                            <span class="badge bg-dark">{{ $doc->requirement->name ?? 'Unknown' }}</span>
                                        </td>
                                        <td>
                                            @if($doc->is_verified)
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Verified</span>
                                            @else
                                                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $doc->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('student.save.documents') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    @foreach($requirements as $req)
                        @php
                            $uploadedDoc = $documents->where('document_requirement_id', $req->id)->first();
                        @endphp

                        <div class="col-md-6">
                            <div class="card h-100 border {{ $req->is_required && !$uploadedDoc ? 'border-danger' : '' }}">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold d-flex align-items-center justify-content-between mb-2">
                                        <span>
                                            {{ $req->name }}
                                            @if($req->is_required)
                                                <span class="text-danger ms-1">*</span>
                                            @endif
                                        </span>
                                        @if($uploadedDoc)
                                            <span class="badge bg-success rounded-pill px-2"><i class="bi bi-check2"></i>
                                                Uploaded</span>
                                        @endif
                                    </h6>
                                    @if($req->description)
                                        <p class="card-text text-muted small mb-3">{{ $req->description }}</p>
                                    @endif

                                    <div class="mt-auto">
                                        <input type="file" name="documents[{{ $req->id }}]" class="form-control form-control-sm"
                                            {{ $req->is_required && !$uploadedDoc ? 'required' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="alert alert-info mt-4 py-2 small">
                    <i class="bi bi-info-circle me-1"></i> Uploading a new file for an existing requirement will overwrite
                    your previous submission. (Max size: 5MB per file)
                </div>

                <div class="d-flex justify-content-between mt-4 border-top pt-3">
                    <a href="{{ route('student.step', 1) }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left me-2"></i>Back</a>
                    <button type="submit" class="btn btn-orange">Upload & Continue <i
                            class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>
@endsection