@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')
    <style>
        .section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #9ca3af;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, #e5e7eb, transparent);
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .simplemaps-wrapper {
            position: relative;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            min-height: 380px;
        }

        #map {
            width: 100%;
            max-width: 550px;
            margin: 0 auto;
        }

        #mapInfoPanel {
            background: linear-gradient(135deg, #fef7f0 0%, #fff5eb 100%);
            border-radius: 16px;
            padding: 1.5rem;
            min-height: 380px;
            max-height: 600px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255, 121, 0, 0.15);
            transition: border-color 0.3s;
        }

        #mapInfoPanel.active {
            border-color: rgba(255, 121, 0, 0.5);
        }

        #mapInfoPanel::-webkit-scrollbar {
            width: 5px;
        }

        #mapInfoPanel::-webkit-scrollbar-thumb {
            background: rgba(255, 121, 0, 0.3);
            border-radius: 10px;
        }

        #mapInfoPanel .info-header {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: sticky;
            top: 0;
            background: linear-gradient(135deg, #fef7f0 0%, #fff5eb 100%);
            padding: 0.5rem 0;
            z-index: 2;
            animation: panelFadeIn 0.3s ease;
        }

        .region-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ff7900;
            display: inline-block;
            margin-right: 4px;
            animation: pulse-dot 1.5s ease infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 121, 0, 0.5);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(255, 121, 0, 0);
            }
        }

        @keyframes panelFadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #mapInfoPanel .academy-item {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 3px solid #ff7900;
            animation: slideUp 0.35s ease both;
        }

        #mapInfoPanel .academy-item:nth-child(2) {
            animation-delay: 0.08s;
        }

        #mapInfoPanel .academy-item:nth-child(3) {
            animation-delay: 0.16s;
        }

        #mapInfoPanel .academy-item:nth-child(4) {
            animation-delay: 0.24s;
        }

        #mapInfoPanel .academy-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        /* Hide simplemaps built-in popup - data shows in our panel instead */
        #map .sm_popup,
        #map .tt_sm {
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        .academy-stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .map-mini-stat {
            text-align: center;
            padding: 0.6rem 0.5rem;
            border-radius: 10px;
            background: #f9fafb;
            flex: 1;
            min-width: 60px;
        }

        .map-mini-stat .num {
            font-size: 1.3rem;
            font-weight: 700;
            line-height: 1;
        }

        .map-mini-stat .lbl {
            font-size: 0.65rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .map-cohort-mini {
            font-size: 0.78rem;
        }

        .map-cohort-mini td,
        .map-cohort-mini th {
            padding: 0.35rem 0.5rem;
        }

        .map-cohort-mini th {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #9ca3af;
        }

        .map-edu-tag {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            margin: 2px;
            background: rgba(255, 121, 0, 0.08);
            color: #b45309;
        }

        .chart-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .chart-card .card-header {
            border-bottom: 1px solid #f3f4f6;
            border-radius: 16px 16px 0 0 !important;
        }

        #academyStatsTabs .nav-link {
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        #academyStatsTabs .nav-link.active {
            background: linear-gradient(135deg, #ff7900, #ff9a40) !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .detail-metric-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .cohort-table th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }

        .cohort-table td {
            font-size: 0.88rem;
        }

        /* ═══════════════════════════════════════════ */
        /* Mobile Responsive Overrides                */
        /* ═══════════════════════════════════════════ */
        @media (max-width: 767.98px) {

            /* Overview header: stack title and buttons vertically */
            .d-flex.justify-content-between.align-items-center.mb-3 {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.5rem;
            }

            .d-flex.justify-content-between.align-items-center.mb-3 .dropdown {
                width: 100%;
            }

            .d-flex.justify-content-between.align-items-center.mb-3 .dropdown .btn {
                width: 100%;
                text-align: left;
            }

            /* KPI cards: smaller icon and text */
            .stat-icon {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                font-size: 1rem;
            }

            .stat-card .card-body {
                padding: 0.75rem !important;
                gap: 0.5rem !important;
            }

            .stat-card .fs-4 {
                font-size: 1.1rem !important;
            }

            .stat-card .small {
                font-size: 0.7rem;
            }

            /* Charts: full width on mobile */
            .chart-card .card-body {
                padding: 0.75rem;
            }

            .chart-card .card-header {
                padding: 0.75rem;
            }

            .chart-card .card-header h6 {
                font-size: 0.8rem;
            }

            /* Map section: reduce height */
            .simplemaps-wrapper {
                min-height: 250px;
                padding: 0.5rem;
            }

            #mapInfoPanel {
                min-height: auto;
                padding: 1rem;
            }

            /* Section titles */
            .section-title {
                font-size: 0.65rem;
                margin-bottom: 0.75rem;
            }

            /* Academy breakdown cards: tighter spacing */
            .card-header.bg-white.border-0.py-3.pb-0 {
                padding: 0.75rem !important;
            }
        }

        @media (max-width: 575.98px) {

            /* Extra small: KPI cards in single column */
            .row.g-3.mb-4>.col-6 {
                width: 50%;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }

            .stat-card .fs-4 {
                font-size: 1rem !important;
            }

            /* Chart containers: shorter */
            .chart-card canvas {
                max-height: 180px;
            }

            /* Map: even smaller */
            .simplemaps-wrapper {
                min-height: 200px;
            }

            #map {
                max-width: 100%;
            }
        }
    </style>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 1: Overview KPIs --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="section-title mb-0" style="flex:1"><i class="bi bi-speedometer2"></i> <span data-en="Overview"
                data-ar="نظرة عامة">Overview</span></div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" id="exportDropdown"
                data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 600;">
                <i class="bi bi-file-earmark-excel me-1"></i> <span data-en="Export to Excel"
                    data-ar="تصدير إلى إكسل">Export to Excel</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="exportDropdown">
                <li>
                    <a class="dropdown-item fw-bold" href="{{ route('admin.export-excel') }}">
                        <i class="bi bi-globe me-2 text-success"></i> <span data-en="All Academies"
                            data-ar="جميع الأكاديميات">All Academies</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <h6 class="dropdown-header text-uppercase" style="font-size: 0.7rem;" data-en="Filtered by Academy"
                        data-ar="تصفية حسب الأكاديمية">Filtered by Academy</h6>
                </li>
                @foreach(\App\Models\Academy::all() as $academy)
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.export-excel', ['academy' => $academy->id]) }}">
                            <i class="bi bi-building me-2 text-muted"></i> {{ $academy->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="pdfExportDropdown"
                data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 600;">
                <i class="bi bi-file-earmark-pdf me-1"></i> <span data-en="View Report" data-ar="عرض التقرير">View
                    Report</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="pdfExportDropdown">
                <li>
                    <a class="dropdown-item fw-bold" href="{{ route('admin.export-pdf') }}" target="_blank">
                        <i class="bi bi-globe me-2 text-primary"></i> <span data-en="All Academies"
                            data-ar="جميع الأكاديميات">All Academies</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <h6 class="dropdown-header text-uppercase" style="font-size: 0.7rem;" data-en="Filtered by Academy"
                        data-ar="تصفية حسب الأكاديمية">Filtered by Academy</h6>
                </li>
                @foreach(\App\Models\Academy::all() as $academy)
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.export-pdf', ['academy' => $academy->id]) }}"
                            target="_blank">
                            <i class="bi bi-building me-2 text-muted"></i> {{ $academy->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(255,121,0,0.1)">
                        <i class="bi bi-people-fill" style="color:#ff7900"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Total Students" data-ar="إجمالي الطلاب">Total Students</div>
                        <div class="fs-4 fw-bold">{{ $total_users }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(255,200,0,0.1)">
                        <i class="bi bi-clock-fill" style="color:#e6a800"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Pending" data-ar="معلّقة">Pending</div>
                        <div class="fs-4 fw-bold">{{ $pending_enrollments }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(50,200,50,0.1)">
                        <i class="bi bi-check-circle-fill text-success"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Accepted" data-ar="مقبول">Accepted</div>
                        <div class="fs-4 fw-bold">{{ $accepted_enrollments }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(205,60,20,0.1)">
                        <i class="bi bi-file-earmark-x-fill" style="color:#cd3c14"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Unverified Docs" data-ar="وثائق غير موثّقة">Unverified Docs
                        </div>
                        <div class="fs-4 fw-bold">{{ $unverified_docs }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(171,140,228,0.1)">
                        <i class="bi bi-award-fill" style="color:#ab8ce4"></i>
                    </div>
                    <div>
                        <div class="small text-muted" data-en="Uni. Graduates" data-ar="خريجو الجامعة">Uni. Graduates</div>
                        <div class="fs-4 fw-bold">{{ $totalGraduated }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    {{-- SECTION 1.5: Academy Analytics Breakdown --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="section-title mb-3"><i class="bi bi-grid-3x3-gap"></i> <span data-en="Academy Analytics Breakdown"
            data-ar="تحليل الأكاديميات">Academy Analytics Breakdown</span></div>
    <div class="row g-3 mb-4">
        @foreach($academyDetailedStats as $name => $data)
            @php
                $pendingCount  = 0;
                $acceptedCount = 0;
                foreach ($data['cohorts'] as $c) {
                    $pendingCount  += ($c['applied']   ?? 0);
                    $acceptedCount += ($c['accepted']  ?? 0);
                }
                $totalStudents  = $data['total'];
                $graduatedCount = $data['graduated_count'];
                $acceptedRatio  = $totalStudents > 0 ? round(($acceptedCount / $totalStudents) * 100) : 0;
                $totalAge1835Academy = array_sum($data['age']);
            @endphp
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px;overflow:hidden;">
                    {{-- Academy Name Header --}}
                    <div class="card-header border-0 py-2 px-3" style="background:linear-gradient(135deg,#ff7900,#ffaa40);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-building text-white" style="font-size:1rem;"></i>
                            <span class="fw-bold" style="font-size:0.85rem;">{{ $name }}</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        {{-- 2×2 stat grid --}}
                        <div class="row g-2 mb-3">
                            {{-- Total Students --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(255,121,0,0.07);">
                                    <div class="fw-bold" style="font-size:1.4rem;color:#ff7900;line-height:1.1;">{{ $totalStudents }}</div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Total" data-ar="الإجمالي">Total</div>
                                </div>
                            </div>
                            {{-- Pending --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(255,193,7,0.1);">
                                    <div class="fw-bold" style="font-size:1.4rem;color:#e6a800;line-height:1.1;">{{ $pendingCount }}</div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Pending" data-ar="معلّق">Pending</div>
                                </div>
                            </div>
                            {{-- Accepted --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(40,167,69,0.08);">
                                    <div class="fw-bold" style="font-size:1.4rem;color:#28a745;line-height:1.1;">{{ $acceptedCount }}</div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Accepted" data-ar="مقبول">Accepted</div>
                                </div>
                            </div>
                            {{-- Uni. Graduates --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(171,140,228,0.1);">
                                    <div class="fw-bold" style="font-size:1.4rem;color:#ab8ce4;line-height:1.1;">{{ $graduatedCount }}</div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Uni. Grads" data-ar="خريجو الجامعة">Uni. Grads</div>
                                </div>
                            </div>
                        </div>
                        {{-- Acceptance rate bar --}}
                        @php
                            $maleCount       = $data['gender']['male']   ?? 0;
                            $femaleCount     = $data['gender']['female'] ?? 0;
                            $notGraduated    = $totalStudents - $graduatedCount;
                            $totalGender     = $maleCount + $femaleCount;
                            $malePct         = $totalGender > 0 ? round(($maleCount   / $totalGender) * 100) : 0;
                        @endphp

                        {{-- Gender + Graduation row --}}
                        <div class="row g-2 mb-3">
                            {{-- Male --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(13,110,253,0.07);">
                                    <div class="fw-bold" style="font-size:1.2rem;color:#0d6efd;line-height:1.1;">
                                        <i class="bi bi-gender-male" style="font-size:0.85rem;"></i> {{ $maleCount }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Male" data-ar="ذكر">Male</div>
                                </div>
                            </div>
                            {{-- Female --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(220,53,69,0.07);">
                                    <div class="fw-bold" style="font-size:1.2rem;color:#dc3545;line-height:1.1;">
                                        <i class="bi bi-gender-female" style="font-size:0.85rem;"></i> {{ $femaleCount }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Female" data-ar="أنثى">Female</div>
                                </div>
                            </div>
                            {{-- Uni. Graduated --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(255,193,7,0.1);">
                                    <div class="fw-bold" style="font-size:1.2rem;color:#d97706;line-height:1.1;">
                                        <i class="bi bi-award-fill" style="font-size:0.8rem;"></i> {{ $graduatedCount }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Graduated" data-ar="خريج">Graduated</div>
                                </div>
                            </div>
                            {{-- Not Graduated --}}
                            <div class="col-6">
                                <div class="rounded-3 text-center py-2 px-1" style="background:rgba(108,117,125,0.07);">
                                    <div class="fw-bold" style="font-size:1.2rem;color:#6c757d;line-height:1.1;">
                                        <i class="bi bi-mortarboard" style="font-size:0.8rem;"></i> {{ $notGraduated }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;" data-en="Not Grad." data-ar="غير خريج">Not Grad.</div>
                                </div>
                            </div>
                            {{-- Age 18–35 Breakdown --}}
                            <div class="col-12">
                                <div class="rounded-3 py-2 px-2" style="background:rgba(75,180,230,0.06);">
                                    <div class="text-muted mb-2" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:.04em;font-weight:600;">
                                        <i class="bi bi-bar-chart-fill me-1" style="color:#4bb4e6;"></i>
                                        <span data-en="Age Distribution" data-ar="توزيع الأعمار">Age Distribution</span>
                                    </div>
                                    @php $maxAge = max(array_values($data['age']) ?: [1]); @endphp
                                    @foreach($data['age'] as $range => $cnt)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div style="width:32px;font-size:0.63rem;color:#6b7280;text-align:right;flex-shrink:0;">{{ $range }}</div>
                                            <div class="flex-grow-1" style="height:10px;background:rgba(0,0,0,0.05);border-radius:6px;overflow:hidden;">
                                                <div style="height:100%;width:{{ $maxAge > 0 ? round(($cnt/$maxAge)*100) : 0 }}%;background:linear-gradient(90deg,#4bb4e6,#74c9f0);border-radius:6px;transition:width .4s;"></div>
                                            </div>
                                            <div style="width:20px;font-size:0.7rem;font-weight:700;color:#4bb4e6;flex-shrink:0;">{{ $cnt }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Gender split bar --}}
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1" style="font-size:0.68rem;">
                                <span style="color:#0d6efd;font-weight:600;"><i class="bi bi-gender-male"></i> {{ $malePct }}%</span>
                                <span style="color:#dc3545;font-weight:600;">{{ 100 - $malePct }}% <i class="bi bi-gender-female"></i></span>
                            </div>
                            <div class="progress" style="height:4px;border-radius:10px;">
                                <div class="progress-bar" style="width:{{ $malePct }}%;background:#0d6efd;border-radius:10px 0 0 10px;"></div>
                                <div class="progress-bar" style="width:{{ 100 - $malePct }}%;background:#dc3545;border-radius:0 10px 10px 0;"></div>
                            </div>
                        </div>

                        {{-- Acceptance rate bar --}}
                        <div>
                            <div class="d-flex justify-content-between mb-1" style="font-size:0.7rem;">
                                <span class="text-muted" data-en="Acceptance Rate" data-ar="نسبة القبول">Acceptance Rate</span>
                                <span class="fw-bold text-success">{{ $acceptedRatio }}%</span>
                            </div>
                            <div class="progress" style="height:5px;border-radius:10px;">
                                <div class="progress-bar bg-success" style="width:{{ $acceptedRatio }}%;border-radius:10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 2: Charts --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="section-title"><i class="bi bi-bar-chart-line"></i> <span data-en="Analytics"
            data-ar="التحليلات">Analytics</span></div>
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Students by Gender" data-ar="الطلاب حسب الجنس">
                        <i class="bi bi-gender-ambiguous me-2 text-muted"></i>Students by Gender
                    </h6>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div style="height: 230px; width: 100%;"><canvas id="genderChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Enrollments by Status" data-ar="التسجيلات حسب الحالة"><i
                            class="bi bi-pie-chart me-2 text-muted"></i>Enrollments by Status</h6>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div style="height: 230px; width: 100%;"><canvas id="statusChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Students by Academy" data-ar="الطلاب حسب الأكاديمية"><i
                            class="bi bi-building me-2 text-muted"></i>Students by Academy</h6>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div style="height: 230px; width: 100%;"><canvas id="academyChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Education Level Chart --}}
        <div class="col-lg-3">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Education Level" data-ar="المستوى التعليمي"><i
                            class="bi bi-mortarboard me-2 text-muted"></i>Education Level</h6>
                </div>
                <div class="card-body">
                    @if(count($educationStats) > 0)
                        <canvas id="educationChart" height="200"></canvas>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-pie-chart" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="mt-2 small" data-en="No data available" data-ar="لا توجد بيانات متاحة">No
                                data available
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- University Chart --}}
        <div class="col-lg-3">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small" data-en="University" data-ar="الجامعة"><i
                            class="bi bi-bank me-2 text-muted"></i>University</h6>
                </div>
                <div class="card-body">
                    @if(count($universityStats) > 0)
                        <canvas id="universityChart" height="200"></canvas>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bank" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="mt-2 small" data-en="No data available" data-ar="لا توجد بيانات متاحة">No
                                data available
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Age Analysis Chart --}}
        <div class="col-lg-3">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Age Analysis (18-35)" data-ar="تحليل العمر (18-35)"><i
                            class="bi bi-calendar-event me-2 text-muted"></i>Age Analysis (18-35)</h6>
                </div>
                <div class="card-body">
                    @php
                        $hasAgeData = false;
                        foreach ($ageStats as $val)
                            if ($val > 0)
                                $hasAgeData = true;
                    @endphp
                    @if($hasAgeData)
                        <canvas id="ageChart" height="200"></canvas>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="mt-2 small" data-en="No data available" data-ar="لا توجد بيانات متاحة">No
                                data available
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Graduated Students Chart --}}
        <div class="col-lg-3">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Uni. Graduates" data-ar="خريجو الجامعة"><i
                            class="bi bi-award me-2 text-muted"></i>Uni. Graduates</h6>
                </div>
                <div class="card-body">
                    @if(count($graduatedStats) > 0)
                        <canvas id="graduatedChart" height="200"></canvas>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-award" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="mt-2 small" data-en="No data available" data-ar="لا توجد بيانات متاحة">No
                                data available
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 3: Interactive Map + Academy Info --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="section-title"><i class="bi bi-geo-alt"></i> <span data-en="Academy Locations"
            data-ar="مواقع الأكاديميات">Academy Locations</span></div>
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card chart-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 small"><i class="bi bi-map me-2 text-muted"></i> <span
                            data-en="Click a highlighted region to see details"
                            data-ar="انقر على منطقة مميزة لعرض التفاصيل">Click a highlighted region to see
                            details</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="simplemaps-wrapper">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div id="mapInfoPanel">
                <div class="text-center py-5" id="mapDefaultMessage" style="margin:auto;">
                    <div
                        style="width:70px;height:70px;border-radius:50%;background:rgba(255,121,0,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="bi bi-hand-index-thumb" style="font-size: 1.8rem; color: #ff7900;"></i>
                    </div>
                    <h6 class="fw-bold text-secondary" data-en="Select a Region" data-ar="اختر منطقة">Select
                        a Region</h6>
                    <p class="text-muted small mb-0 px-3"
                        data-en="Click on an orange-highlighted region on the map to view the academies and student demographics in that area."
                        data-ar="انقر على منطقة مميزة بالبرتقالي على الخريطة لعرض الأكاديميات والتركيبة السكانية للطلاب في تلك المنطقة.">
                        Click on an orange-highlighted region on the map to view all academy details in that
                        area.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 5: Recent Activity --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="section-title"><i class="bi bi-clock-history"></i> <span data-en="Recent Activity"
            data-ar="النشاط الأخير">Recent Activity</span></div>
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Recent Students" data-ar="أحدث الطلاب"><i
                            class="bi bi-person-plus me-2 text-muted"></i>Recent Students</h6>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-orange" data-en="View All"
                        data-ar="عرض الكل">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" data-en="Email" data-ar="البريد الإلكتروني">Email</th>
                                    <th data-en="Joined" data-ar="تاريخ الانضمام">Joined</th>
                                    <th data-en="Status" data-ar="الحالة">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_users as $u)
                                    <tr>
                                        <td class="small ps-3">{{ $u->email }}</td>
                                        <td class="small text-muted">{{ $u->created_at->diffForHumans() }}</td>
                                        <td>
                                            @if($u->is_active)
                                                <span class="badge bg-success" data-en="Active" data-ar="نشط">Active</span>
                                            @else
                                                <span class="badge bg-secondary" data-en="Inactive"
                                                    data-ar="غير نشط">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0 small" data-en="Recent Enrollments" data-ar="أحدث التسجيلات"><i
                            class="bi bi-journal-check me-2 text-muted"></i>Recent Enrollments</h6>
                    <a href="{{ route('admin.enrollments') }}" class="btn btn-sm btn-outline-orange" data-en="View All"
                        data-ar="عرض الكل">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" data-en="Student" data-ar="الطالب">Student</th>
                                    <th data-en="Cohort" data-ar="الدفعة">Cohort</th>
                                    <th data-en="Status" data-ar="الحالة">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_enrollments as $e)
                                    <tr>
                                        <td class="small ps-3">
                                            {{ $e->user->profile->first_name_en ?? $e->user->email }}
                                        </td>
                                        <td class="small text-muted">{{ Str::limit($e->cohort->name, 30) }}</td>
                                        <td>
                                            @php $c = ['applied' => 'warning', 'accepted' => 'success', 'rejected' => 'danger', 'enrolled' => 'primary', 'graduated' => 'success', 'dropped' => 'secondary']; @endphp
                                            <span
                                                class="badge bg-{{ $c[$e->status] ?? 'secondary' }} {{ $e->status == 'applied' ? 'text-dark' : '' }}">{{ ucfirst($e->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 6: Activity Monitoring --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="section-title"><i class="bi bi-activity"></i> <span data-en="Activity Monitoring"
            data-ar="مراقبة النشاط">Activity Monitoring</span></div>

    {{-- Activity KPI Row --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card chart-card text-center h-100" style="border-left: 4px solid #ff7900;">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1"><i class="bi bi-lightning-charge"></i> Today's
                        Activity</div>
                    <div class="fw-bold" style="font-size:2rem;color:#ff7900;">{{ $todayActivityCount }}
                    </div>
                    <a href="{{ route('admin.activities') }}" class="btn btn-sm btn-outline-orange mt-2"
                        style="font-size:0.75rem;">View All Logs</a>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card chart-card text-center h-100" style="border-left: 4px solid #dc3545;">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1"><i class="bi bi-x-circle"></i> Not Started</div>
                    <div class="fw-bold" style="font-size:2rem;color:#dc3545;">
                        {{ $progressCounts['not_started'] }}
                    </div>
                    <a href="{{ route('admin.missed-data') }}" class="btn btn-sm btn-outline-danger mt-2"
                        style="font-size:0.75rem;">View Missed Data</a>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card chart-card text-center h-100" style="border-left: 4px solid #ffc107;">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1"><i class="bi bi-hourglass-split"></i> In Progress
                    </div>
                    <div class="fw-bold" style="font-size:2rem;color:#ffc107;">
                        {{ $progressCounts['in_progress'] }}
                    </div>
                    <a href="{{ route('admin.user-progress') }}" class="btn btn-sm btn-outline-warning mt-2"
                        style="font-size:0.75rem;">View Progress</a>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card chart-card text-center h-100" style="border-left: 4px solid #28a745;">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1"><i class="bi bi-check2-circle"></i> Completed</div>
                    <div class="fw-bold" style="font-size:2rem;color:#28a745;">
                        {{ $progressCounts['completed'] }}
                    </div>
                    <a href="{{ route('admin.user-progress') }}" class="btn btn-sm btn-outline-success mt-2"
                        style="font-size:0.75rem;">View Details</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="card chart-card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="fw-bold mb-0 small"><i class="bi bi-clock-history me-2 text-muted"></i>Recent
                Activity Log</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.missed-data') }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Missed Data
                </a>
                <a href="{{ route('admin.user-progress') }}" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-person-lines-fill me-1"></i>User Progress
                </a>
                <a href="{{ route('admin.activities') }}" class="btn btn-sm btn-outline-orange">View All</a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($recentActivities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 small">Type</th>
                                <th class="small">Action</th>
                                <th class="small">Title</th>
                                <th class="small">User</th>
                                <th class="small">Time</th>
                                <th class="small">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                                <tr>
                                    <td class="ps-3">
                                        @php
                                            $colors = ['registration' => 'primary', 'profile' => 'info', 'documents' => 'warning', 'enrollment' => 'success', 'questionnaire' => 'secondary', 'submission' => 'success', 'verification' => 'info', 'login' => 'dark', 'logout' => 'secondary', 'admin_action' => 'danger'];
                                            $labels = ['registration' => 'Registration', 'profile' => 'Profile', 'documents' => 'Documents', 'enrollment' => 'Enrollment', 'questionnaire' => 'Questionnaire', 'submission' => 'Submission', 'verification' => 'Verification', 'login' => 'Login', 'logout' => 'Logout', 'admin_action' => 'Admin'];
                                        @endphp
                                        <span class="badge bg-{{ $colors[$activity->type] ?? 'secondary' }}"
                                            style="font-size:0.7rem;">
                                            {{ $labels[$activity->type] ?? ucfirst($activity->type) }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $activity->action }}</td>
                                    <td class="small">{{ Str::limit($activity->title, 40) }}</td>
                                    <td class="small">
                                        @if($activity->user)
                                            <a href="{{ route('admin.user-progress.detail', $activity->user) }}"
                                                class="text-decoration-none" style="color:#ff7900;">
                                                {{ Str::limit($activity->user->email, 25) }}
                                            </a>
                                        @elseif($activity->admin)
                                            <span class="text-danger small">{{ $activity->admin->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $activity->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('admin.activities.show', $activity) }}"
                                            class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:0.7rem;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-activity" style="font-size:2.5rem;opacity:0.3;"></i>
                    <p class="mt-2 small">No activities logged yet.<br>Activities will appear here as users
                        interact with the
                        system.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SCRIPTS --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Simplemaps --}}
    <script src="{{ asset('map/mapdata.js') }}"></script>
    <script>
        // === MUST be defined     BEFORE countrymap.js loads ===
        // Global data for map interaction
        var __mapDemographics = @json($normalizedCityDemographics);
        var __mapAcademyData = @json($academyMapData);
        var __academyFullStats = @json($academyDetailedStats);
        var __mapPanelCharts = [];

        // Disable built-in simplemaps popups — use our side panel instead
        simplemaps_countrymap_mapdata.main_settings.popups = 'off';
        var __mapStateToCity = {
            'JOR849': 'Aqaba',
            'JOR851': 'Amman',
            'JOR854': 'Irbid',
            'JOR857': 'Balqa',
            'JOR860': 'Zarqa'
        };

        // Map city names to state IDs (reverse lookup)
        var __cityToState = {};
        for (var sid in __mapStateToCity) {
            __cityToState[__mapStateToCity[sid]] = sid;
        }

        // Update mapdata with dynamic demographics
        for (var stateId in simplemaps_countrymap_mapdata.state_specific) {
            var stateData = simplemaps_countrymap_mapdata.state_specific[stateId];
            if (__mapStateToCity[stateId]) {
                var cityName = __mapStateToCity[stateId];
                var studentCount = __mapDemographics[cityName] || 0;
                var cityAcademies = __mapAcademyData[cityName] || [];

                var desc = '<strong>' + cityName + '</strong><br>';
                desc += studentCount + ' Student' + (studentCount !== 1 ? 's' : '') + '<br>';
                if (cityAcademies.length > 0) {
                    cityAcademies.forEach(function (a) {
                        desc += '<br><strong>' + a.name + '</strong>';
                        if (a.cohort_names && a.cohort_names.length > 0) {
                            desc += '<br><em>' + a.cohort_names.join(', ') + '</em>';
                        }
                    });
                }
                if (studentCount > 0 || cityAcademies.length > 0) {
                    stateData.color = 'rgba(255, 121, 0, 0.4)';
                    stateData.hover_color = 'rgba(255, 121, 0, 1)';
                    stateData.url = "javascript:showCityInfoPanel('" + cityName.replace(/'/g, "\\'") + "');";
                } else {
                    stateData.url = ""; // Not clickable if empty
                }
            }
        }

        // Update location pins with academy & cohort details
        for (var locId in simplemaps_countrymap_mapdata.locations) {
            var loc = simplemaps_countrymap_mapdata.locations[locId];
            var pinCity = loc.name;
            if (pinCity && __mapAcademyData[pinCity]) {
                var pinAcademies = __mapAcademyData[pinCity];
                var pinDesc = '<strong>' + pinCity + '</strong>';
                pinAcademies.forEach(function (a) {
                    pinDesc += '<br><strong>' + a.name + '</strong> (' + a.students_count + ' students)';
                    if (a.cohort_names && a.cohort_names.length > 0) {
                        a.cohort_names.forEach(function (cn) {
                            pinDesc += '<br>&nbsp;&nbsp;• ' + cn;
                        });
                    }
                });
                loc.description = pinDesc;
                loc.color = '#ff7900';
                loc.url = "javascript:showCityInfoPanel('" + pinCity.replace(/'/g, "\\'") + "');";
            }
        }

        // === The click callback MUST be defined before countrymap.js loads ===
        function simplemaps_countrymap_click(id) {
            var cityName = __mapStateToCity[id];
            if (cityName) {
                showCityInfoPanel(cityName);
            }
        }

        // Also handle location (pin) clicks
        function simplemaps_countrymap_location_click(id) {
            var loc = simplemaps_countrymap_mapdata.locations[id];
            if (loc && loc.name && __mapAcademyData[loc.name]) {
                showCityInfoPanel(loc.name);
            }
        }

        function showCityInfoPanel(cityName) {
            var panel = document.getElementById('mapInfoPanel');
            var defaultMsg = document.getElementById('mapDefaultMessage');
            var cityAcademies = __mapAcademyData[cityName] || [];
            var studentCount = __mapDemographics[cityName] || 0;

            // Destroy old charts
            __mapPanelCharts.forEach(function (c) { c.destroy(); });
            __mapPanelCharts.length = 0;

            // Add active class to panel
            document.getElementById('mapInfoPanel').classList.add('active');

            var html = '<div class="info-header">' +
                '<span class="region-dot"></span>' +
                '<i class="bi bi-geo-alt-fill" style="color:#ff7900;"></i> ' + cityName +
                '<span class="ms-auto academy-stat-badge" style="background:rgba(255,121,0,0.1);color:#ff7900;">' +
                '<i class="bi bi-people-fill"></i> ' + studentCount + ' students</span></div>';

            if (cityAcademies.length === 0) {
                html += '<div class="text-muted small text-center py-4"><i class="bi bi-building" style="font-size:2rem;color:#e5e7eb;"></i><br><span class="mt-2 d-block">No academies in this region yet.</span></div>';
            } else {
                cityAcademies.forEach(function (a, idx) {
                    var stats = __academyFullStats[a.name] || { total: 0, gender: {}, education: {}, cohorts: {} };
                    var maleCount = stats.gender.male || stats.gender.Male || 0;
                    var femaleCount = stats.gender.female || stats.gender.Female || 0;

                    html += '<div class="academy-item">';
                    html += '<div class="d-flex justify-content-between align-items-center mb-2">';
                    html += '<div class="fw-bold text-dark" style="font-size:0.95rem;"><i class="bi bi-building me-1" style="color:#ff7900;"></i>' + a.name + '</div></div>';

                    // Stats Row
                    html += '<div class="d-flex gap-2 mb-3">';
                    html += '<div class="map-mini-stat"><div class="num" style="color:#ff7900;">' + stats.total + '</div><div class="lbl">Total</div></div>';
                    html += '<div class="map-mini-stat"><div class="num" style="color:#2C8BFF;">' + maleCount + '</div><div class="lbl"><i class="bi bi-gender-male"></i> Male</div></div>';
                    html += '<div class="map-mini-stat"><div class="num" style="color:#FF69B4;">' + femaleCount + '</div><div class="lbl"><i class="bi bi-gender-female"></i> Female</div></div>';
                    html += '<div class="map-mini-stat"><div class="num" style="color:#50be87;">' + a.cohorts_count + '</div><div class="lbl">Cohorts</div></div>';
                    html += '</div>';

                    // Gender mini chart
                    if (maleCount > 0 || femaleCount > 0) {
                        html += '<div style="height:110px;margin-bottom:0.75rem;"><canvas id="mapGender-' + idx + '"></canvas></div>';
                    }

                    // Education Tags
                    var eduKeys = Object.keys(stats.education);
                    if (eduKeys.length > 0) {
                        html += '<div class="mb-2"><div style="font-size:0.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;"><i class="bi bi-mortarboard me-1"></i>Education</div>';
                        eduKeys.forEach(function (k) {
                            html += '<span class="map-edu-tag">' + k.charAt(0).toUpperCase() + k.slice(1) + ' <strong>' + stats.education[k] + '</strong></span>';
                        });
                        html += '</div>';
                    }

                    // Cohort Table
                    var cohortKeys = Object.keys(stats.cohorts);
                    if (cohortKeys.length > 0) {
                        html += '<div style="font-size:0.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;"><i class="bi bi-list-columns-reverse me-1"></i>Cohorts</div>';
                        html += '<table class="table table-sm map-cohort-mini mb-0"><thead><tr><th>Name</th><th class="text-center">Total</th><th class="text-center">Accepted</th><th class="text-center">Rejected</th></tr></thead><tbody>';
                        cohortKeys.forEach(function (name) {
                            var cs = stats.cohorts[name];
                            html += '<tr><td class="fw-medium">' + name + '</td>';
                            html += '<td class="text-center"><span class="badge bg-secondary rounded-pill" style="font-size:0.7rem;">' + (cs.total || 0) + '</span></td>';
                            html += '<td class="text-center"><span class="badge bg-success rounded-pill" style="font-size:0.7rem;">' + (cs.accepted || 0) + '</span></td>';
                            html += '<td class="text-center"><span class="badge bg-danger rounded-pill" style="font-size:0.7rem;">' + (cs.rejected || 0) + '</span></td></tr>';
                        });
                        html += '</tbody></table>';
                    }
                    html += '</div>';
                });
            }

            if (panel) {
                panel.innerHTML = html;
                panel.style.display = 'block';
            }
            if (defaultMsg) defaultMsg.style.display = 'none';

            // Create inline gender charts after DOM update
            setTimeout(function () {
                cityAcademies.forEach(function (a, idx) {
                    var stats = __academyFullStats[a.name] || { gender: {} };
                    var maleCount = stats.gender.male || stats.gender.Male || 0;
                    var femaleCount = stats.gender.female || stats.gender.Female || 0;
                    var canvasEl = document.getElementById('mapGender-' + idx);
                    if (canvasEl && (maleCount > 0 || femaleCount > 0)) {
                        var chart = new Chart(canvasEl, {
                            type: 'doughnut',
                            data: { labels: ['Male', 'Female'], datasets: [{ data: [maleCount, femaleCount], backgroundColor: ['#2C8BFF', '#FF69B4'], borderWidth: 0 }] },
                            options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { display: true, position: 'right', labels: { padding: 8, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } } } }
                        });
                        __mapPanelCharts.push(chart);
                    }
                });
            }, 50);

            // Scroll panel to top
            document.getElementById('mapInfoPanel').scrollTop = 0;
        }
    </script>
    <script src="{{ asset('map/countrymap.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createChart = (id, type, labels, data, colors) => {
                const el = document.getElementById(id);
                if (!el) return;
                const isPieOrDoughnut = ['pie', 'doughnut'].includes(type);
                new Chart(el, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: isPieOrDoughnut ? 'Distribution' : 'Count',
                            data: data,
                            backgroundColor: colors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', display: true, labels: { padding: 12, usePointStyle: true, pointStyle: 'circle' } }
                        },
                        scales: isPieOrDoughnut ? {} : {
                            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            };

            // Global overview charts
            const genderData = @json($genderStats);
            createChart('genderChart', 'pie',
                Object.keys(genderData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                Object.values(genderData),
                ['#2C8BFF', '#FF69B4']
            );

            const statusData = @json($enrollmentStats);
            createChart('statusChart', 'doughnut',
                Object.keys(statusData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                Object.values(statusData),
                ['#ffc107', '#198754', '#dc3545', '#0d6efd', '#20c997', '#6c757d']
            );

            const academyData = @json($academyStats);
            createChart('academyChart', 'bar',
                Object.keys(academyData),
                Object.values(academyData),
                ['#ff7900', '#50be87', '#4bb4e6', '#ab8ce4', '#ff4d4d']
            );

            // Demographics Overview Charts
            const educationData = @json($educationStats);
            if (Object.keys(educationData).length > 0) {
                createChart('educationChart', 'doughnut',
                    Object.keys(educationData),
                    Object.values(educationData),
                    ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997']
                );
            }

            const universityData = @json($universityStats);
            if (Object.keys(universityData).length > 0) {
                createChart('universityChart', 'bar',
                    Object.keys(universityData),
                    Object.values(universityData),
                    ['#ff7900', '#2C8BFF', '#50be87', '#ab8ce4', '#FF69B4', '#ffc107', '#dc3545', '#20c997']
                );
            }

            // Age Analysis Chart
            const ageData = @json($ageStats);
            if (Object.keys(ageData).length > 0) {
                createChart('ageChart', 'bar',
                    Object.keys(ageData),
                    Object.values(ageData),
                    ['#4bb4e6', '#ff7900', '#50be87', '#ab8ce4']
                );
            }

            // Graduated Students Chart
            const graduatedData = @json($graduatedStats);
            if (Object.keys(graduatedData).length > 0) {
                createChart('graduatedChart', 'pie',
                    Object.keys(graduatedData),
                    Object.values(graduatedData),
                    ['#ff7900', '#50be87', '#4bb4e6', '#ab8ce4', '#ff4d4d', '#20c997']
                );
            }
        });
    </script>
@endsection