@extends('layouts.admin')
@section('page-title', 'Student Details')
@section('content')
    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary mb-3"><i
            class="bi bi-arrow-left me-1"></i>Back to Students</a>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    @php
                        $personalPhoto = $user->documents->filter(fn($d) => $d->documentRequirement && stripos($d->documentRequirement->name, 'Personal Photo') !== false)->first();
                    @endphp
                    @if($personalPhoto && $personalPhoto->file_path)
                        <img src="{{ asset('storage/' . $personalPhoto->file_path) }}" 
                             alt="Personal Photo" 
                             class="rounded-circle mb-3"
                             style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ff7900;">
                    @else
                        <div class="mx-auto mb-3"
                            style="width:120px;height:120px;border-radius:50%;background:var(--orange-primary);display:flex;align-items:center;justify-content:center;color:white;font-size:2rem;font-weight:700;">
                            {{ strtoupper(substr($user->profile->first_name_en ?? $user->email, 0, 2)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ trim(($user->profile->first_name_en ?? '') . ' ' . ($user->profile->second_name_en ?? '') . ' ' . ($user->profile->third_name_en ?? '') . ' ' . ($user->profile->last_name_en ?? '')) ?: 'N/A' }}</h5>
                    <div class="small fw-semibold text-secondary mb-1">{{ trim(($user->profile->first_name_ar ?? '') . ' ' . ($user->profile->second_name_ar ?? '') . ' ' . ($user->profile->third_name_ar ?? '') . ' ' . ($user->profile->last_name_ar ?? '')) }}</div>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    @if($u->is_active ?? $user->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                    
                    <div class="mt-2">
                        <form method="POST" action="{{ route('admin.users.filtration', $user) }}" class="d-inline-block">
                            @csrf
                            <select name="filtration_status" class="form-select form-select-sm bg-light fw-bold text-center @if($user->filtration_status == 'Accepted for Interview') text-success border-success @elseif($user->filtration_status == 'Rejected') text-danger border-danger @else text-warning border-warning @endif" style="font-size: 0.8rem; border-radius: 20px;" onchange="this.form.submit()">
                                <option value="" {{ is_null($user->filtration_status) ? 'selected' : '' }}>Status: Pending</option>
                                <option value="Accepted for Interview" {{ $user->filtration_status === 'Accepted for Interview' ? 'selected' : '' }}>Accepted for Interview</option>
                                <option value="Rejected" {{ $user->filtration_status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </form>
                    </div>
                    <hr>
                    <div class="text-start small">
                        <p><strong>ID Number:</strong> {{ $user->profile->id_number ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $user->profile->phone ?? 'N/A' }} 
                            @if($user->profile && $user->profile->phone_verified)
                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.65rem;">
                                    <i class="bi bi-patch-check-fill me-1"></i>Verified
                                </span>
                            @endif
                        </p>
                        <p><strong>Gender:</strong> {{ ucfirst($user->profile->gender ?? 'N/A') }}</p>
                        <p><strong>DOB:</strong> {{ $user->profile->date_of_birth?->format('M d, Y') ?? 'N/A' }}</p>
                        <p><strong>Nationality:</strong> {{ $user->profile->nationality ?? 'N/A' }}</p>
                        <p><strong>Location:</strong> {{ $user->profile->country ?? 'N/A' }}, {{ $user->profile->city ?? 'N/A' }} {{ $user->profile->neighborhood ? ' - ' . $user->profile->neighborhood : '' }}</p>
                        <p><strong>Education:</strong> {{ $user->profile->education_level ?? 'N/A' }} @if($user->profile && $user->profile->field_of_study) in {{ $user->profile->field_of_study }} @endif @if($user->profile && $user->profile->major) ({{ $user->profile->major }}) @endif</p>
                        
                        @if($user->profile && $user->profile->university)
                            <hr class="my-2">
                            <p><strong>University:</strong> {{ $user->profile->university }}</p>
                            <p><strong>Graduation:</strong> @if($user->profile->is_graduated) Graduated ({{ $user->profile->graduation_year }}) @else Expected ({{ $user->profile->expected_graduation_year }}) @endif</p>
                            @if($user->profile->gpa_value)
                                <p><strong>GPA:</strong> {{ $user->profile->gpa_value }} ({{ ucwords(str_replace('_', ' ', $user->profile->gpa_type)) }})</p>
                            @endif
                        @endif

                        <p class="mb-0 mt-3 pt-2 border-top"><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            {{-- Enrollments --}}
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clipboard-check text-orange me-2"></i>Enrollments</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cohort</th>
                                <th>Academy</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->enrollments as $e)
                                <tr>
                                    <td>{{ $e->cohort->name }}</td>
                                    <td class="small text-muted">{{ $e->cohort->academy->name }}</td>
                                    <td><span
                                            class="badge bg-{{ ['applied' => 'warning', 'accepted' => 'success', 'rejected' => 'danger'][$e->status] ?? 'secondary' }} {{ $e->status == 'applied' ? 'text-dark' : '' }}">{{ ucfirst($e->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No enrollments</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Documents --}}
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark text-orange me-2"></i>Documents</h6>
                </div>
                <div class="card-body bg-light p-4">
                    @if($user->documents->count() > 0)
                        <div class="row g-4">
                            @foreach($user->documents as $doc)
                                @php
                                    $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <div class="col-sm-6 col-md-4">
                                    <div class="card h-100 border-0 shadow-sm overflow-hidden rounded-3">
                                        @if($isImage)
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="d-block bg-dark" title="Click to open full image">
                                                <img src="{{ asset('storage/' . $doc->file_path) }}" class="card-img-top opacity-75 object-fit-cover" style="height: 150px; transition: opacity 0.3s;" onmouseover="this.classList.remove('opacity-75')" onmouseout="this.classList.add('opacity-75')" alt="Document">
                                            </a>
                                        @else
                                            <div class="card-img-top bg-dark d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-white text-decoration-none text-center opacity-75" style="transition: opacity 0.3s;" onmouseover="this.classList.remove('opacity-75')" onmouseout="this.classList.add('opacity-75')" title="Click to open document">
                                                    <i class="bi bi-file-earmark-{{ strtolower($ext) === 'pdf' ? 'pdf' : 'text' }} display-4"></i>
                                                    <div class="mt-2 small text-uppercase fw-bold tracking-wide">{{ $ext }} FILE</div>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="card-body p-3 text-center d-flex flex-column">
                                            <h6 class="card-title fw-bold text-truncate mb-2" title="{{ $doc->requirement->name ?? 'Unknown Document' }}">
                                                {{ $doc->requirement->name ?? 'Unknown Document' }}
                                            </h6>
                                            <div class="mb-3">
                                                @if($doc->is_verified)
                                                    <span class="badge bg-success-subtle text-success px-2 py-1 border border-success-subtle"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 border border-warning-subtle"><i class="bi bi-clock me-1"></i>Pending</span>
                                                @endif
                                            </div>
                                            <div class="mt-auto">
                                                @if($doc->is_verified)
                                                    <button class="btn btn-sm btn-light text-muted w-100 disabled" disabled><i class="bi bi-check-all me-2"></i>Verified</button>
                                                @elseif($doc->rejection_reason !== null)
                                                    <button class="btn btn-sm btn-danger-subtle text-danger-emphasis w-100 disabled" disabled><i class="bi bi-x-circle me-2"></i>Rejected</button>
                                                    <div class="small text-danger mt-2 text-start p-2 bg-danger-subtle border border-danger-subtle rounded"><i class="bi bi-info-circle me-1"></i>{{ $doc->rejection_reason }}</div>
                                                @else
                                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                                         <form method="POST" action="{{ route('admin.documents.verify', $doc) }}" class="w-50 m-0 p-0">
                                                             @csrf
                                                             <button type="submit" class="btn btn-sm btn-success w-100 fw-bold"><i class="bi bi-check2-circle me-1"></i>Verify</button>
                                                         </form>
                                                         <button type="button" class="btn btn-sm btn-outline-danger w-50 fw-bold btn-reject-toggle" data-doc-id="{{ $doc->id }}">
                                                             <i class="bi bi-x-circle me-1"></i>Reject
                                                         </button>
                                                     </div>
                                                     
                                                     {{-- Inline Reject Form --}}
                                                     <div class="reject-form-container mt-3 d-none" id="rejectForm{{ $doc->id }}">
                                                         <form method="POST" action="/admin/documents/{{ $doc->id }}/unverify" class="reject-form bg-white p-3 border border-danger-subtle rounded shadow-sm" data-doc-id="{{ $doc->id }}">
                                                             @csrf
                                                             <div class="mb-3">
                                                                 <label for="rejection_reason_{{ $doc->id }}" class="form-label fw-bold small text-danger">Reason for Rejection <span class="text-danger">*</span></label>
                                                                 <textarea class="form-control form-control-sm" id="rejection_reason_{{ $doc->id }}" name="rejection_reason" rows="3" required placeholder="Provide details for the student..."></textarea>
                                                             </div>
                                                             <div class="d-flex justify-content-between gap-2">
                                                                 <button type="button" class="btn btn-xs btn-light w-50 btn-cancel-reject" data-doc-id="{{ $doc->id }}">Cancel</button>
                                                                 <button type="submit" class="btn btn-xs btn-danger w-50"><i class="bi bi-send-fill me-1"></i>Reject</button>
                                                             </div>
                                                         </form>
                                                     </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-folder-x display-5 mb-3 text-secondary opacity-50 d-block"></i>
                            <p class="mb-0 fs-5">No documents uploaded yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Orange Coursat Certificates --}}
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-award text-orange me-2"></i>Orange Coursat Certificates</h6>
                </div>
                <div class="card-body bg-light p-4">
                    @if($user->coursatCertificates->count() > 0)
                        <div class="row g-4">
                            @php $courses = ['html' => 'HTML', 'css' => 'CSS', 'javascript' => 'JavaScript']; @endphp
                            @foreach($user->coursatCertificates as $cert)
                                @php
                                    $ext = pathinfo($cert->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <div class="col-sm-6 col-md-4">
                                    <div class="card h-100 border-0 shadow-sm overflow-hidden rounded-3">
                                        @if($isImage)
                                            <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="d-block bg-dark" title="Click to open full image">
                                                <img src="{{ asset('storage/' . $cert->file_path) }}" class="card-img-top opacity-75 object-fit-cover" style="height: 150px; transition: opacity 0.3s;" onmouseover="this.classList.remove('opacity-75')" onmouseout="this.classList.add('opacity-75')" alt="Certificate">
                                            </a>
                                        @else
                                            <div class="card-img-top bg-dark d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="text-white text-decoration-none text-center opacity-75" style="transition: opacity 0.3s;" onmouseover="this.classList.remove('opacity-75')" onmouseout="this.classList.add('opacity-75')" title="Click to open certificate">
                                                    <i class="bi bi-file-earmark-{{ strtolower($ext) === 'pdf' ? 'pdf' : 'text' }} display-4 text-orange"></i>
                                                    <div class="mt-2 small text-uppercase fw-bold tracking-wide">{{ $ext }} FILE</div>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="card-body p-3 text-center d-flex flex-column">
                                            <h6 class="card-title fw-bold text-truncate mb-1">
                                                {{ $courses[$cert->course_name] ?? ucfirst($cert->course_name) }}
                                            </h6>
                                            <p class="text-muted small mb-0">{{ $cert->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-award display-5 mb-3 text-secondary opacity-50 d-block"></i>
                            <p class="mb-0 fs-5">No certificates uploaded yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Assessments --}}
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-journal-check text-orange me-2"></i>Assessments</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Assessment</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->assessmentSubmissions as $sub)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $sub->assessment->title }}</div>
                                        <div class="small text-muted">
                                            {{ ucfirst(str_replace('_', ' ', $sub->assessment->type)) }}
                                        </div>
                                    </td>
                                    <td>{{ $sub->score ?? '-' }} / {{ $sub->assessment->max_score }}</td>
                                    <td>
                                        <span class="badge bg-{{ $sub->status == 'graded' ? 'success' : 'warning text-dark' }}">
                                            {{ ucfirst(str_replace('_', ' ', $sub->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.submissions.grade', $sub->id) }}"
                                            class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No assessments</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Reject Form
            document.querySelectorAll('.btn-reject-toggle').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const docId = this.dataset.docId;
                    const formContainer = document.getElementById('rejectForm' + docId);
                    formContainer.classList.remove('d-none');
                    // Focus the textarea
                    formContainer.querySelector('textarea').focus();
                    // Hide the action buttons
                    this.closest('.d-flex').classList.add('d-none');
                });
            });

            // Cancel Rejection
            document.querySelectorAll('.btn-cancel-reject').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const docId = this.dataset.docId;
                    const formContainer = document.getElementById('rejectForm' + docId);
                    formContainer.classList.add('d-none');
                    // Show the action buttons back
                    formContainer.closest('.card-body').querySelector('.d-flex').classList.remove('d-none');
                });
            });

            document.querySelectorAll('.reject-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>...';
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show premium success toast
                            const toastHTML = `
                                <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
                                    <div id="successToast" class="toast align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="d-flex">
                                            <div class="toast-body p-3">
                                                <i class="bi bi-check-circle-fill me-2"></i>
                                                <strong>Success!</strong> ${data.message}
                                            </div>
                                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            document.body.insertAdjacentHTML('beforeend', toastHTML);
                            const toastElement = document.getElementById('successToast');
                            const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
                            toast.show();
                            
                            // Reload after a short delay to let user see the toast
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            });
        });
    </script>
@endsection