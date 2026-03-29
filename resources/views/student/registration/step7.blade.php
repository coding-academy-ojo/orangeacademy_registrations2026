@extends('layouts.student')
@section('title', 'Step 7 - Review & Submit')
@section('content')
    @include('student.registration._progress', ['step' => 7])
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-check text-orange me-2"></i>Review Your Application</h5>
        </div>
        <div class="card-body p-4">
            {{-- Profile --}}
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-person text-orange me-2"></i>Profile</h6>
                    <a href="{{ route('student.step', 1) }}" class="btn btn-sm btn-outline-orange">Edit</a>
                </div>
                @if($user->profile)
                    <div class="row g-2 small">
                        <div class="col-md-12 mb-1">
                            <strong>Name (English):</strong>
                            {{ trim($user->profile->first_name_en . ' ' . $user->profile->second_name_en . ' ' . $user->profile->third_name_en . ' ' . $user->profile->last_name_en) }}
                            <br>
                            <strong>Name (Arabic):</strong>
                            {{ trim($user->profile->first_name_ar . ' ' . $user->profile->second_name_ar . ' ' . $user->profile->third_name_ar . ' ' . $user->profile->last_name_ar) }}
                        </div>
                        <div class="col-md-4"><strong>Phone:</strong> {{ $user->profile->phone }}</div>
                        <div class="col-md-4"><strong>Gender:</strong> {{ ucfirst($user->profile->gender) }}</div>
                        <div class="col-md-4"><strong>DOB:</strong> {{ $user->profile->date_of_birth?->format('M d, Y') }}</div>
                        <div class="col-md-4"><strong>Nationality:</strong> {{ $user->profile->nationality }}</div>
                        <div class="col-md-8">
                            <strong>Location:</strong> {{ $user->profile->country }}, {{ $user->profile->city }}
                            @if($user->profile->neighborhood)- {{ $user->profile->neighborhood }}@endif
                        </div>
                        <div class="col-md-12 mt-2 pt-2 border-top">
                            <strong>Education:</strong> {{ $user->profile->education_level }}
                            @if($user->profile->field_of_study) in {{ $user->profile->field_of_study }} @endif
                            @if($user->profile->major) ({{ $user->profile->major }}) @endif
                        </div>
                        @if($user->profile->university)
                            <div class="col-md-6"><strong>University:</strong> {{ $user->profile->university }}</div>
                            <div class="col-md-6">
                                <strong>Graduation:</strong>
                                @if($user->profile->is_graduated)
                                    Graduated ({{ $user->profile->graduation_year }})
                                @else
                                    Expected ({{ $user->profile->expected_graduation_year }})
                                @endif
                            </div>
                        @endif
                        @if($user->profile->gpa_value)
                            <div class="col-md-12"><strong>GPA:</strong> {{ $user->profile->gpa_value }}
                                ({{ ucwords(str_replace('_', ' ', $user->profile->gpa_type)) }})</div>
                        @endif

                        {{-- Health & Accessibility Review --}}
                        <div class="col-12 mt-3 pt-2 border-top">
                            <h6 class="fw-bold mb-2" style="font-size: 0.85rem;"><i class="bi bi-heart-pulse text-orange me-2"></i>Health & Accessibility</h6>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded border border-light-subtle h-100">
                                        <div class="fw-bold small text-secondary mb-1">Accessibility Needs</div>
                                        <div>
                                            @if($user->profile->has_accessibility_needs)
                                                <span class="text-danger fw-bold">Yes:</span> {{ $user->profile->accessibility_details }}
                                            @else
                                                <span class="text-success">None</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded border border-light-subtle h-100">
                                        <div class="fw-bold small text-secondary mb-1">Chronic Illness</div>
                                        <div>
                                            @if($user->profile->has_illness)
                                                <span class="text-danger fw-bold">Yes:</span> {{ $user->profile->illness_details }}
                                            @else
                                                <span class="text-success">None</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Emergency Contacts Review --}}
                        <div class="col-12 mt-3 pt-2 border-top">
                            <h6 class="fw-bold mb-2" style="font-size: 0.85rem;"><i class="bi bi-people text-orange me-2"></i>Emergency Contacts</h6>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded border border-light-subtle h-100">
                                        <div class="fw-bold small text-secondary mb-1">Relative 1 (Primary)</div>
                                        <div class="mb-1"><strong>Name:</strong> {{ $user->profile->relative1_name }}</div>
                                        <div class="mb-1"><strong>Relation:</strong> {{ ucfirst($user->profile->relative1_relation) }}</div>
                                        <div><strong>Phone:</strong> {{ $user->profile->relative1_phone }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded border border-light-subtle h-100">
                                        <div class="fw-bold small text-secondary mb-1">Relative 2 (Secondary)</div>
                                        @if($user->profile->relative2_name)
                                            <div class="mb-1"><strong>Name:</strong> {{ $user->profile->relative2_name }}</div>
                                            <div class="mb-1"><strong>Relation:</strong> {{ ucfirst($user->profile->relative2_relation) }}</div>
                                            <div><strong>Phone:</strong> {{ $user->profile->relative2_phone }}</div>
                                        @else
                                            <div class="text-muted italic small py-2">Not provided</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <span class="text-muted small">Not completed</span>
                @endif
            </div>

            {{-- Documents --}}
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark text-orange me-2"></i>Documents
                        ({{ $user->documents->count() }})</h6>
                    <a href="{{ route('student.step', 2) }}" class="btn btn-sm btn-outline-orange">Edit</a>
                </div>
                @foreach($user->documents as $doc)
                    <span
                        class="badge bg-dark me-1 mb-1">{{ str_replace('_', ' ', ucfirst($doc->requirement->name ?? 'Unknown')) }}</span>
                @endforeach
                @if($user->documents->isEmpty())
                    <span class="text-muted small">No documents uploaded</span>
                @endif
            </div>

            {{-- Orange Coursat --}}
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-award text-orange me-2"></i>Orange Coursat Certificates</h6>
                    <a href="{{ route('student.step', 3) }}" class="btn btn-sm btn-outline-orange">Edit</a>
                </div>
                @php $courses = ['html' => 'HTML', 'css' => 'CSS', 'javascript' => 'JavaScript']; @endphp
                @foreach($user->coursatCertificates as $cert)
                    <span class="badge bg-success me-1 mb-1">
                        <i
                            class="bi bi-check2-circle me-1"></i>{{ $courses[$cert->course_name] ?? ucfirst($cert->course_name) }}
                    </span>
                @endforeach
                @if($user->coursatCertificates->isEmpty())
                    <span class="text-muted small">No certificates uploaded</span>
                @endif
            </div>

            {{-- Enrollment --}}
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-building text-orange me-2"></i>Academy & Cohort</h6>
                    <a href="{{ route('student.step', 4) }}" class="btn btn-sm btn-outline-orange">Edit</a>
                </div>
                @forelse($user->enrollments as $enr)
                    <div class="small">
                        <strong>{{ $enr->cohort->name }}</strong> at {{ $enr->cohort->academy->name }}
                        <span class="badge bg-info text-white ms-2">{{ ucfirst($enr->status) }}</span>
                    </div>
                @empty
                    <span class="text-muted small">No program selected</span>
                @endforelse
            </div>

            {{-- Assessments --}}
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-journal-check text-orange me-2"></i>Assessments</h6>
                    <a href="{{ route('student.step', 5) }}" class="btn btn-sm btn-outline-orange">View</a>
                </div>
                @forelse($user->assessmentSubmissions as $sub)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-dark">{{ $sub->assessment->title }}</span>
                        @if($sub->status === 'graded')
                            <span class="badge bg-success">{{ $sub->score }}/{{ $sub->assessment->max_score }}</span>
                        @elseif($sub->status === 'submitted')
                            <span class="badge bg-warning text-dark">Pending Review</span>
                        @else
                            <span class="badge bg-secondary">In Progress</span>
                        @endif
                    </div>
                @empty
                    <span class="text-muted small">No assessments taken</span>
                @endforelse
            </div>

            {{-- Questionnaire --}}
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-question-circle text-orange me-2"></i>Questionnaire</h6>
                    <a href="{{ route('student.step', 6) }}" class="btn btn-sm btn-outline-orange">Edit</a>
                </div>
                @if($user->answers->count())
                    <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i>Completed
                        ({{ $user->answers->count() }} answers)</span>
                @else
                    <span class="text-muted small">Not completed</span>
                @endif
            </div>

            {{-- Submit --}}
            <form method="POST" action="{{ route('student.submit') }}">
                @csrf
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('student.step', 6) }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left me-2"></i>Back</a>
                    <button type="submit" class="btn btn-orange btn-lg px-5">
                        <i class="bi bi-send-fill me-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection