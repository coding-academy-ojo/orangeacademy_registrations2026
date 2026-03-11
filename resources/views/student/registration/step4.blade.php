@extends('layouts.student')
@section('title', 'Step 4 - Academy & Cohort')

@section('content')
    @include('student.registration._progress', ['step' => 4])

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-4 border-0 rounded-top-4">
            <h5 class="mb-0 fw-bold"><i class="bi bi-building text-orange me-2"></i>Select Academy</h5>
            <p class="text-muted small mt-1 mb-0" data-en="Choose the academy closely located to you."
                data-ar="اختر الأكاديمية الأقرب إليك.">Choose the academy closely located to you.</p>
        </div>
        <div class="card-body p-4 pt-0">
            <form method="POST" action="{{ route('student.save.enrollment') }}">
                @csrf

                <div class="row g-4 mb-4">
                    @foreach($academies as $academy)
                        @if($academy->cohorts->count())
                            @php
                                // Default fallbacks
                                $mapLink = '#';
                                $img = 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&q=80&w=400';

                                if ($academy->location_link) {
                                    $mapLink = $academy->location_link;
                                } elseif (stripos($academy->name, 'amman') !== false || stripos($academy->name, 'عمان') !== false) {
                                    $mapLink = 'https://share.google/XozgryZJ7KMOtRVYT';
                                } elseif (stripos($academy->name, 'irbid') !== false || stripos($academy->name, 'إربد') !== false) {
                                    $mapLink = 'https://share.google/X6uFlL5pRYOBWXFfq';
                                } elseif (stripos($academy->name, 'zarqa') !== false || stripos($academy->name, 'زرقاء') !== false) {
                                    $mapLink = 'https://share.google/8wCS0fi2EVErzGdMD';
                                } elseif (stripos($academy->name, 'balqa') !== false || stripos($academy->name, 'بلقاء') !== false) {
                                    $mapLink = 'https://maps.app.goo.gl/JFycSFEZNE77qhqN6';
                                }

                                if ($academy->image) {
                                    $img = asset('storage/' . $academy->image);
                                } elseif (stripos($academy->name, 'amman') !== false || stripos($academy->name, 'عمان') !== false) {
                                    $img = 'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?auto=format&fit=crop&q=80&w=400'; // Modern city feel
                                } elseif (stripos($academy->name, 'irbid') !== false || stripos($academy->name, 'إربد') !== false) {
                                    $img = 'https://images.unsplash.com/photo-1596484552834-6a58f850e0a1?auto=format&fit=crop&q=80&w=400'; // University/greenery feel
                                } elseif (stripos($academy->name, 'zarqa') !== false || stripos($academy->name, 'زرقاء') !== false) {
                                    $img = 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&q=80&w=400'; // Urban feel
                                } elseif (stripos($academy->name, 'balqa') !== false || stripos($academy->name, 'بلقاء') !== false) {
                                    $img = 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&q=80&w=400'; // Academic feel
                                }
                            @endphp
                            <div class="col-md-6 col-lg-3">
                                <label class="d-block w-100">
                                    <input type="radio" name="academy_selection" id="academy_{{ $academy->id }}" class="d-none peer-academy-radio academy_selection"
                                        value="{{ $academy->id }}" onchange="showCohorts({{ $academy->id }})">
                                    <div class="card h-100 border-2 academy-card shadow-sm"
                                        style="cursor:pointer; border-radius: 12px; transition: all 0.3s; overflow:hidden;">
                                        <!-- Image -->
                                        <div style="height: 140px; overflow: hidden; position: relative;">
                                            <img src="{{ $img }}" class="card-img-top w-100 h-100" alt="{{ $academy->name }}"
                                                style="object-fit: cover; transition: transform 0.5s;">
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <div class="academy-check-icon shadow-sm d-flex align-items-center justify-content-center bg-white rounded-circle"
                                                    style="width: 28px; height: 28px; opacity: 0; transform: scale(0.5); transition: 0.3s;">
                                                    <i class="bi bi-check-lg text-success" style="font-size: 1.2rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                                            <h6 class="fw-bold mb-2 academy-title" style="transition: 0.3s;">{{ $academy->name }}
                                            </h6>
                                            <div>
                                                <a href="{{ $mapLink }}" target="_blank"
                                                    class="text-decoration-none small text-muted d-inline-block mt-1 map-link"
                                                    onclick="event.stopPropagation()">
                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                                    <span data-en="Location Link" data-ar="رابط الموقع">Location Link</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Cohorts Containers -->
                <div id="cohorts_master_container" style="display:none;">
                    <h5 class="fw-bold mb-3 mt-4 border-top pt-4">
                        <i class="bi bi-layers text-orange me-2"></i>
                        <span data-en="Select Program" data-ar="البرنامج الأكاديمي">Select Program</span>
                    </h5>
                    @foreach($academies as $academy)
                        @if($academy->cohorts->count())
                            <div class="academy-cohort-group" id="cohort_group_{{ $academy->id }}" style="display: none;">
                                @if($academy->registration_rules)
                                    <div class="alert alert-warning mb-4 shadow-sm border-0" style="background-color: #fff9e6; border-radius: 12px; border-left: 5px solid #ffc107 !important;">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-info-circle-fill text-warning fs-4 me-3 mt-1"></i>
                                            <div>
                                                <h6 class="fw-bold mb-2 text-dark">
                                                    <span data-en="Registration Rules & Conditions" data-ar="شروط وأحكام التسجيل">Registration Rules & Conditions</span>
                                                </h6>
                                                <div class="text-dark small lh-lg" style="white-space: pre-line;">
                                                    {{ $academy->registration_rules }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row g-3">
                                    @foreach($academy->cohorts as $cohort)
                                        <div class="col-md-6">
                                            <label class="d-block w-100 h-100">
                                                <input type="radio" name="cohort_id" id="cohort_{{ $cohort->id }}" value="{{ $cohort->id }}" class="d-none peer-radio cohort_id"
                                                    {{ optional($enrollment)->cohort_id == $cohort->id ? 'checked' : '' }} required>
                                                <div class="card h-100 border-2 p-3 cohort-card shadow-sm"
                                                    style="cursor:pointer;border-color:var(--orange-grey-300); border-radius: 12px; transition: 0.3s;">
                                                    <h6 class="fw-bold mb-1">{{ $cohort->name }}</h6>
                                                    <p class="text-muted small mb-3">{{ Str::limit($cohort->description, 80) }}</p>
                                                    <div class="d-flex gap-3 small mt-auto">
                                                        <span><i
                                                                class="bi bi-calendar3 me-1"></i>{{ $cohort->start_date->format('M d') }}
                                                            - {{ $cohort->end_date->format('M d, y') }}</span>
                                                        <span class="badge bg-success opacity-75">{{ ucfirst($cohort->status) }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if($academies->flatMap->cohorts->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                        <span data-en="No active programs available at the moment."
                            data-ar="لا يوجد برامج نشطة في الوقت الحالي.">No active programs available at the moment.</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <a href="{{ route('student.step', 3) }}" class="btn btn-outline-secondary px-4 py-2 rounded-3"><i
                            class="bi bi-arrow-left me-2"></i><span data-en="Back" data-ar="رجوع">Back</span></a>
                    <button type="submit" class="btn btn-orange px-4 py-2 rounded-3 shadow-sm"><span
                            data-en="Save & Continue" data-ar="حفظ ومتابعة">Save & Continue</span> <i
                            class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>

    @section('styles')
        <style>
            /* Cohort Active State (Orange) */
            input[name="cohort_id"]:checked+.cohort-card {
                border-color: var(--orange-primary) !important;
                background: rgba(255, 121, 0, 0.05);
                /* Very light orange */
                box-shadow: 0 4px 12px rgba(255, 121, 0, 0.15) !important;
                transform: translateY(-2px);
            }

            /* Academy Hover State */
            .academy-card:hover {
                border-color: #198754 !important;
                /* Green border on hover */
                transform: translateY(-4px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
            }

            .academy-card:hover .card-img-top {
                transform: scale(1.05);
            }

            /* Academy Active State (Green as requested UI) */
            input[name="academy_selection"]:checked+.academy-card {
                border-color: #198754 !important;
                /* Bootstrap success green */
                background: rgba(25, 135, 84, 0.03);
                /* Extremely light green tint */
                box-shadow: 0 8px 16px rgba(25, 135, 84, 0.15) !important;
                transform: translateY(-4px);
            }

            /* Green text for selected academy */
            input[name="academy_selection"]:checked+.academy-card .academy-title {
                color: #198754 !important;
            }

            /* Show check icon for selected academy */
            input[name="academy_selection"]:checked+.academy-card .academy-check-icon {
                opacity: 1 !important;
                transform: scale(1) !important;
            }

            .map-link:hover {
                color: #dc3545 !important;
                text-decoration: underline !important;
            }
        </style>
    @endsection

    @section('scripts')
        <script>
            function showCohorts(academyId) {
                const masterContainer = document.getElementById('cohorts_master_container');
                if (masterContainer) {
                    masterContainer.style.display = 'block';

                    // Re-apply language translations if active on the newly revealed segment
                    if (window.OA_Lang && window.OA_Lang.current === 'ar') {
                        window.OA_Lang.apply('ar');
                    }
                }

                document.querySelectorAll('.academy-cohort-group').forEach(el => {
                    el.style.display = 'none';
                });

                const group = document.getElementById('cohort_group_' + academyId);
                if (group) {
                    group.style.display = 'block';

                    // smooth scroll down to cohorts smoothly
                    const y = group.getBoundingClientRect().top + window.scrollY - 100;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            }

            // On load, if there's an already selected cohort, we should expand the corresponding academy.
            document.addEventListener('DOMContentLoaded', () => {
                const checkedCohort = document.querySelector('input[name="cohort_id"]:checked');
                if (checkedCohort) {
                    const group = checkedCohort.closest('.academy-cohort-group');
                    if (group) {
                        // Get academyId from the group's ID (cohort_group_123)
                        const academyId = group.id.replace('cohort_group_', '');
                        const academyRadio = document.querySelector('input[name="academy_selection"][value="' + academyId + '"]');
                        if (academyRadio) {
                            academyRadio.checked = true;
                            showCohorts(academyId);
                        }
                    }
                }
            });
        </script>
    @endsection
@endsection