@extends('layouts.admin')
@section('page-title', 'Academy Interviews')

@section('content')
    <style>
        .nav-pills .nav-link {
            font-weight: 600;
            color: #4b5563;
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            transition: all 0.2s;
            border: 1px solid transparent;
            background: white;
        }

        .nav-pills .nav-link:hover {
            background: #f9fafb;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #ff7900, #ff9a40) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(255, 121, 0, 0.2);
        }

        .glass-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .glass-card .card-header {
            background: rgba(249, 250, 251, 0.5);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .criteria-row {
            background: #f9fafb;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px dashed #e5e7eb;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .score-input {
            text-align: center;
            font-weight: bold;
            color: #ff7900;
        }

        .total-score-box {
            background: rgba(255, 121, 0, 0.1);
            border: 2px solid #ff7900;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }

        .total-score-num {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ff7900;
            line-height: 1;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-person-video2 text-orange me-2"></i> Interviews</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.interviews.export', ['academy' => $activeTab]) }}" class="btn btn-success" target="_blank">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Accepted
            </a>
            <button class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#criteriaModal">
                <i class="bi bi-sliders me-1"></i> Assessment Checklist
            </button>
        </div>
    </div>

    <!-- Academy Tabs -->
    <ul class="nav nav-pills gap-2 mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'all' ? 'active' : '' }}"
                href="{{ route('admin.interviews.index', ['academy' => 'all', 'status' => $statusFilter]) }}">
                <i class="bi bi-grid me-1"></i> All Academies
            </a>
        </li>
        @foreach($academies as $academy)
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == $academy->id ? 'active' : '' }}"
                    href="{{ route('admin.interviews.index', ['academy' => $academy->id, 'status' => $statusFilter]) }}">
                    {{ $academy->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Search Filter -->
    <div class="mb-3">
        <form method="GET" action="{{ route('admin.interviews.index') }}" class="d-flex gap-2">
            <input type="hidden" name="academy" value="{{ $activeTab }}">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search by name, email or phone..." value="{{ request('search') }}">
                @if(request('search'))
                    <a href="{{ route('admin.interviews.index', ['academy' => $activeTab, 'status' => $statusFilter]) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
                <button class="btn btn-orange" type="submit">Search</button>
            </div>
        </form>
    </div>

    <!-- Status Filters -->
    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('admin.interviews.index', ['academy' => $activeTab, 'status' => 'all']) }}"
            class="btn btn-sm {{ $statusFilter == 'all' ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill px-4">All
            Statuses</a>
        <a href="{{ route('admin.interviews.index', ['academy' => $activeTab, 'status' => 'pending']) }}"
            class="btn btn-sm {{ $statusFilter == 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill px-4">Pending
            Review</a>
        <a href="{{ route('admin.interviews.index', ['academy' => $activeTab, 'status' => 'enrolled']) }}"
            class="btn btn-sm {{ $statusFilter == 'enrolled' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-4"><i
                class="bi bi-check-circle me-1"></i> Accepted to Join</a>
        <a href="{{ route('admin.interviews.index', ['academy' => $activeTab, 'status' => 'rejected']) }}"
            class="btn btn-sm {{ $statusFilter == 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-4"><i
                class="bi bi-x-circle me-1"></i> Rejected</a>
    </div>

    <!-- Students List for Active Academy -->
    <div class="glass-card mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i> Students Accepted for Interviews</h6>
            @php
                $totalPoints = $criteria->sum('weight');
            @endphp
            <span class="badge bg-light text-dark border"><i class="bi bi-trophy text-orange"></i> Max Score:
                {{ $totalPoints }}</span>
        </div>
        <div class="card-body p-0">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Student Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Cohort</th>
                                <th>Status</th>
                                <th>Interview Score</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                @php
                                    $enrollment = $student->enrollments->first();
                                    $evaluation = $enrollment ? $enrollment->interviewEvaluation : null;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle bg-light text-orange fw-bold d-flex align-items-center justify-content-center border"
                                                style="width: 38px; height: 38px; border-radius: 50%;">
                                                {{ strtoupper(substr($student->profile->first_name_en ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $student->profile->first_name_en ?? 'Unknown' }}
                                                    {{ $student->profile->last_name_en ?? '' }}
                                                </div>
                                                <div class="small text-muted">{{ $student->profile->city ?? 'Unknown City' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->profile->phone ?? '-' }}</td>
                                    <td>
                                        @if($enrollment && $enrollment->cohort)
                                            <span class="badge bg-light text-dark border">{{ $enrollment->cohort->name }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>No Cohort</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment && $enrollment->status == 'enrolled')
                                            <span class="badge bg-success rounded-pill px-3 py-2"><i
                                                    class="bi bi-check-circle me-1"></i> Enrolled</span>
                                        @elseif($enrollment && $enrollment->status == 'rejected')
                                            <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i>
                                                Rejected</span>
                                        @elseif($enrollment && $enrollment->status == 'pending')
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i
                                                    class="bi bi-hourglass-split me-1"></i> Pending Result</span>
                                        @else
                                            <span class="badge bg-info rounded-pill px-3 py-2"><i
                                                    class="bi bi-person-check me-1"></i> Awaiting Interview</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($evaluation)
                                            <div class="fw-bold text-orange" style="font-size: 1.1rem;">
                                                {{ $evaluation->total_score }} <span class="text-muted small fw-normal">/
                                                    {{ $totalPoints }}</span>
                                            </div>
                                            <div class="small text-muted">Evaluated by {{ $evaluation->admin->name ?? 'Admin' }}</div>
                                        @else
                                            <span class="text-muted fst-italic">Not evaluated</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($enrollment)
                                            <button class="btn btn-sm btn-outline-orange"
                                                onclick="openEvaluationModal({{ $enrollment->id }}, '{{ json_encode($evaluation ? $evaluation->scores : []) }}', '{{ addslashes($evaluation->notes ?? '') }}', '{{ $student->profile->first_name_en ?? 'Student' }}')">
                                                @if($evaluation)
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                @else
                                                    <i class="bi bi-star"></i> Evaluate
                                                @endif
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-orange"
                                                onclick="openNewEnrollmentModal({{ $student->id }}, '{{ $student->profile->first_name_en ?? 'Student' }}', '{{ $activeTab }}')">
                                                <i class="bi bi-plus-circle me-1"></i> Enroll & Evaluate
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 text-light-orange mb-3 d-block"></i>
                    <h5>No students pending.</h5>
                    <p>There are currently no students accepted for interviews in this academy.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Criteria Settings Modal -->
    <div class="modal fade" id="criteriaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.interviews.criteria', $activeTab) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-sliders text-orange me-2"></i> Assessment Checklist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <p class="text-muted small mb-4">Define the specific interview criteria and max score (weight) for
                        students applying to this academy.</p>

                    <div id="criteriaContainer">
                        @forelse($criteria as $index => $c)
                            <div class="criteria-row">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control" name="criteria[{{ $index }}][name]"
                                        value="{{ $c->name }}" placeholder="Criterion Name (e.g. Technical Skills)" required>
                                </div>
                                <div style="width: 150px;">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="criteria[{{ $index }}][weight]"
                                            value="{{ $c->weight }}" placeholder="Max" min="1" max="100" required>
                                        <span class="input-group-text">pts</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i
                                        class="bi bi-trash"></i></button>
                            </div>
                        @empty
                            <div class="criteria-row">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control" name="criteria[0][name]"
                                        placeholder="Criterion Name (e.g. Technical Skills)" required>
                                </div>
                                <div style="width: 150px;">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="criteria[0][weight]" placeholder="Max"
                                            min="1" max="100" required>
                                        <span class="input-group-text">pts</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i
                                        class="bi bi-trash"></i></button>
                            </div>
                        @endforelse
                    </div>

                    <button type="button" class="btn btn-dashed w-100 mt-2 text-primary border-primary"
                        onclick="addCriteriaRow()" style="border-style: dashed; background: transparent;">
                        <i class="bi bi-plus-lg"></i> Add Criterion
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-orange">Save Checklist</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Evaluation Modal -->
    <div class="modal fade" id="evaluationModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <form id="evalForm" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="action" id="evalAction" value="save">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-star-fill text-orange me-2"></i> Evaluate <span
                            id="evalStudentName" class="text-orange"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($criteria->count() == 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> You must define an assessment checklist for
                            this academy first!
                        </div>
                    @else
                        <div class="row">
                            <!-- Checklist -->
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-3 text-uppercase text-muted"
                                    style="font-size:0.8rem; letter-spacing:1px;">Checklist</h6>
                                @foreach($criteria as $c)
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded-3 border">
                                        <div>
                                            <div class="fw-bold">{{ $c->name }}</div>
                                            <div class="small text-muted">Max Score: {{ $c->weight }}</div>
                                        </div>
                                        <div style="width: 100px;">
                                            <input type="number" class="form-control form-control-lg score-input eval-input"
                                                name="scores[{{ $c->id }}]" id="score_{{ $c->id }}" min="0" max="{{ $c->weight }}"
                                                data-weight="{{ $c->weight }}" placeholder="0" required>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mb-3 mt-4">
                                    <label class="fw-bold mb-2">Admin Notes / Comments</label>
                                    <textarea name="notes" id="evalNotes" class="form-control" rows="3"
                                        placeholder="Enter any specific feedback about this candidate..."></textarea>
                                </div>
                            </div>

                            <!-- Live Score calculation -->
                            <div class="col-md-4">
                                <div class="position-sticky" style="top: 20px;">
                                    <h6 class="fw-bold mb-3 text-uppercase text-muted"
                                        style="font-size:0.8rem; letter-spacing:1px;">Total Score</h6>
                                    <div class="total-score-box">
                                        <div class="total-score-num" id="liveTotalScore">0</div>
                                        <div class="text-muted mt-1 fw-bold">out of {{ $totalPoints }}</div>
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div id="liveProgressBar" class="progress-bar bg-orange" role="progressbar"
                                                style="width: 0%;"></div>
                                        </div>
                                        <div id="passFailBadge" class="mt-3 badge bg-secondary w-100 py-2 fs-6">Pending</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if($criteria->count() > 0)
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger" onclick="submitEval('reject')"><i
                                    class="bi bi-x-circle me-1"></i> Reject</button>
                            <button type="button" class="btn btn-secondary" onclick="submitEval('save')"><i
                                    class="bi bi-save me-1"></i> Save Draft</button>
                            <button type="button" class="btn btn-success" onclick="submitEval('accept')"><i
                                    class="bi bi-check-circle me-1"></i> Accept to Join</button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- New Enrollment & Evaluation Modal -->
    <div class="modal fade" id="newEnrollmentModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <form id="newEnrollmentForm" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="action" id="newEvalAction" value="save">
                <input type="hidden" name="academy_id" id="newAcademyId" value="">
                <input type="hidden" name="cohort_id" id="newCohortId" value="">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill text-orange me-2"></i> Enroll & Evaluate <span
                            id="newEvalStudentName" class="text-orange"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($criteria->count() == 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> You must define an assessment checklist for
                            this academy first!
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Select Academy</label>
                            <select id="academySelect" class="form-select" required onchange="updateCohorts()">
                                <option value="">-- Select Academy --</option>
                                @foreach($academies as $academy)
                                    <option value="{{ $academy->id }}">{{ $academy->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Select Cohort</label>
                            <select id="cohortSelect" class="form-select" required disabled onchange="document.getElementById('newCohortId').value = this.value">
                                <option value="">-- Select Academy First --</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-3 text-uppercase text-muted"
                                    style="font-size:0.8rem; letter-spacing:1px;">Interview Checklist</h6>
                                @foreach($criteria as $c)
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded-3 border">
                                        <div>
                                            <div class="fw-bold">{{ $c->name }}</div>
                                            <div class="small text-muted">Max Score: {{ $c->weight }}</div>
                                        </div>
                                        <div style="width: 100px;">
                                            <input type="number" class="form-control form-control-lg score-input new-eval-input"
                                                name="scores[{{ $c->id }}]" id="new_score_{{ $c->id }}" min="0" max="{{ $c->weight }}"
                                                data-weight="{{ $c->weight }}" placeholder="0" required>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mb-3 mt-4">
                                    <label class="fw-bold mb-2">Admin Notes / Comments</label>
                                    <textarea name="notes" id="newEvalNotes" class="form-control" rows="3"
                                        placeholder="Enter any specific feedback about this candidate..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-sticky" style="top: 20px;">
                                    <h6 class="fw-bold mb-3 text-uppercase text-muted"
                                        style="font-size:0.8rem; letter-spacing:1px;">Total Score</h6>
                                    <div class="total-score-box">
                                        <div class="total-score-num" id="newLiveTotalScore">0</div>
                                        <div class="text-muted mt-1 fw-bold">out of {{ $totalPoints }}</div>
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div id="newLiveProgressBar" class="progress-bar bg-orange" role="progressbar"
                                                style="width: 0%;"></div>
                                        </div>
                                        <div id="newPassFailBadge" class="mt-3 badge bg-secondary w-100 py-2 fs-6">Pending</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if($criteria->count() > 0)
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger" onclick="submitNewEval('reject')"><i
                                    class="bi bi-x-circle me-1"></i> Reject</button>
                            <button type="button" class="btn btn-secondary" onclick="submitNewEval('save')"><i
                                    class="bi bi-save me-1"></i> Save Draft</button>
                            <button type="button" class="btn btn-success" onclick="submitNewEval('accept')"><i
                                    class="bi bi-check-circle me-1"></i> Accept to Join</button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        let criteriaIndex = {{ $criteria->count() > 0 ? $criteria->count() : 1 }};
        
        const cohortsByAcademy = @json(\App\Models\Cohort::with('academy')->get()->groupBy('academy_id'));

        function addCriteriaRow() {
            const container = document.getElementById('criteriaContainer');
            const row = document.createElement('div');
            row.className = 'criteria-row';
            row.innerHTML = `
                        <div class="flex-grow-1">
                            <input type="text" class="form-control" name="criteria[${criteriaIndex}][name]" placeholder="Criterion Name" required>
                        </div>
                        <div style="width: 150px;">
                            <div class="input-group">
                                <input type="number" class="form-control" name="criteria[${criteriaIndex}][weight]" placeholder="Max" min="1" max="100" required>
                                <span class="input-group-text">pts</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
                    `;
            container.appendChild(row);
            criteriaIndex++;
        }

        function updateCohorts() {
            const academyId = document.getElementById('academySelect').value;
            const cohortSelect = document.getElementById('cohortSelect');
            const academyIdInt = parseInt(academyId);
            
            cohortSelect.innerHTML = '<option value="">-- Select Cohort --</option>';
            document.getElementById('newAcademyId').value = academyId;
            document.getElementById('newCohortId').value = '';
            
            if (cohortsByAcademy[academyIdInt]) {
                cohortSelect.disabled = false;
                cohortsByAcademy[academyIdInt].forEach(cohort => {
                    const option = document.createElement('option');
                    option.value = cohort.id;
                    option.textContent = cohort.name;
                    cohortSelect.appendChild(option);
                });
            } else {
                cohortSelect.disabled = true;
            }
        }

        function openEvaluationModal(enrollmentId, scoresJson, notes, name) {
            document.getElementById('evalForm').action = `/admin/interviews/evaluate/${enrollmentId}`;
            document.getElementById('evalStudentName').innerText = name;
            document.getElementById('evalNotes').value = notes;

            let scores = {};
            try { if (scoresJson) scores = JSON.parse(scoresJson); } catch (e) { }

            document.querySelectorAll('.eval-input').forEach(input => {
                const critId = input.name.match(/\d+/)[0];
                input.value = scores[critId] !== undefined ? scores[critId] : '';
            });

            calculateTotal();
            new bootstrap.Modal(document.getElementById('evaluationModal')).show();
        }

        function submitEval(action) {
            document.getElementById('evalAction').value = action;

            // Custom validation before submit
            let valid = true;
            document.querySelectorAll('.eval-input').forEach(input => {
                let val = parseFloat(input.value);
                let max = parseFloat(input.getAttribute('max'));
                if (isNaN(val) || val > max || val < 0) {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (valid) {
                document.getElementById('evalForm').submit();
            } else {
                alert('Please ensure all scores are filled correctly within their maximum limits.');
            }
        }

        function calculateTotal() {
            let total = 0;
            let maxTotal = {{ $totalPoints ?? 0 }};

            document.querySelectorAll('.eval-input').forEach(input => {
                let val = parseFloat(input.value) || 0;
                let max = parseFloat(input.getAttribute('max'));
                if (val > max) { val = max; input.value = val; } // Auto-correct overflow
                total += val;
            });

            document.getElementById('liveTotalScore').innerText = total;

            let percentage = maxTotal > 0 ? (total / maxTotal) * 100 : 0;
            let bar = document.getElementById('liveProgressBar');
            bar.style.width = percentage + '%';

            let badge = document.getElementById('passFailBadge');
            if (percentage >= 70) {
                bar.className = 'progress-bar bg-success';
                badge.className = 'mt-3 badge bg-success w-100 py-2 fs-6';
                badge.innerText = 'Passing Score';
            } else if (percentage >= 40) {
                bar.className = 'progress-bar bg-warning text-dark';
                badge.className = 'mt-3 badge bg-warning text-dark w-100 py-2 fs-6';
                badge.innerText = 'Borderline';
            } else {
                bar.className = 'progress-bar bg-danger';
                badge.className = 'mt-3 badge bg-danger w-100 py-2 fs-6';
                badge.innerText = 'Failing Score';
            }
        }

        // Attach event listeners for real-time calculation
        document.querySelectorAll('.eval-input').forEach(input => {
            input.addEventListener('input', calculateTotal);
            input.addEventListener('change', calculateTotal);
        });

        // New Enrollment Modal Functions
        function openNewEnrollmentModal(userId, name, academyId) {
            document.getElementById('newEnrollmentForm').action = `/admin/interviews/evaluate-user/${userId}`;
            document.getElementById('newEvalStudentName').innerText = name;
            document.getElementById('newAcademyId').value = academyId === 'all' ? '' : academyId;
            document.getElementById('newCohortId').value = '';
            document.getElementById('cohortSelect').value = '';
            document.getElementById('academySelect').value = academyId === 'all' ? '' : academyId;
            document.getElementById('newEvalNotes').value = '';
            
            // Reset cohort select
            const cohortSelect = document.getElementById('cohortSelect');
            if (academyId !== 'all' && academyId) {
                cohortSelect.disabled = false;
                cohortSelect.innerHTML = '<option value="">-- Select Cohort --</option>';
                if (cohortsByAcademy[academyId]) {
                    cohortsByAcademy[academyId].forEach(cohort => {
                        const option = document.createElement('option');
                        option.value = cohort.id;
                        option.textContent = cohort.name;
                        cohortSelect.appendChild(option);
                    });
                }
            } else {
                cohortSelect.disabled = true;
                cohortSelect.innerHTML = '<option value="">-- Select Academy First --</option>';
            }
            
            document.querySelectorAll('.new-eval-input').forEach(input => {
                input.value = '';
            });
            
            calculateNewTotal();
            new bootstrap.Modal(document.getElementById('newEnrollmentModal')).show();
        }

        function submitNewEval(action) {
            const cohortId = document.getElementById('newCohortId').value;
            if (!cohortId) {
                alert('Please select a cohort first!');
                return;
            }
            
            document.getElementById('newEvalAction').value = action;

            let valid = true;
            document.querySelectorAll('.new-eval-input').forEach(input => {
                let val = parseFloat(input.value);
                let max = parseFloat(input.getAttribute('max'));
                if (isNaN(val) || val > max || val < 0) {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (valid) {
                document.getElementById('newEnrollmentForm').submit();
            } else {
                alert('Please ensure all scores are filled correctly within their maximum limits.');
            }
        }

        function calculateNewTotal() {
            let total = 0;
            let maxTotal = {{ $totalPoints ?? 0 }};

            document.querySelectorAll('.new-eval-input').forEach(input => {
                let val = parseFloat(input.value) || 0;
                let max = parseFloat(input.getAttribute('max'));
                if (val > max) { val = max; input.value = val; }
                total += val;
            });

            document.getElementById('newLiveTotalScore').innerText = total;

            let percentage = maxTotal > 0 ? (total / maxTotal) * 100 : 0;
            let bar = document.getElementById('newLiveProgressBar');
            bar.style.width = percentage + '%';

            let badge = document.getElementById('newPassFailBadge');
            if (percentage >= 70) {
                bar.className = 'progress-bar bg-success';
                badge.className = 'mt-3 badge bg-success w-100 py-2 fs-6';
                badge.innerText = 'Passing Score';
            } else if (percentage >= 40) {
                bar.className = 'progress-bar bg-warning text-dark';
                badge.className = 'mt-3 badge bg-warning text-dark w-100 py-2 fs-6';
                badge.innerText = 'Borderline';
            } else {
                bar.className = 'progress-bar bg-danger';
                badge.className = 'mt-3 badge bg-danger w-100 py-2 fs-6';
                badge.innerText = 'Failing Score';
            }
        }

        document.querySelectorAll('.new-eval-input').forEach(input => {
            input.addEventListener('input', calculateNewTotal);
            input.addEventListener('change', calculateNewTotal);
        });
    </script>
@endsection