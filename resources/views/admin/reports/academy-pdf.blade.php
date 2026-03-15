<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orange Coding Academy | Analytics Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --orange-main: #FF7900;
            --orange-black: #000000;
            --orange-white: #FFFFFF;
            --orange-grey: #F4F4F4;
            --text-dark: #333333;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: var(--orange-white);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
        }

        .oca-nav {
            background-color: var(--orange-black);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 4px solid var(--orange-main);
        }

        .oca-logo-square {
            background-color: var(--orange-main);
            width: 45px;
            height: 45px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 15px;
        }

        .report-hero {
            background: linear-gradient(135deg, var(--orange-black) 0%, #1a1a1a 100%);
            color: var(--orange-white);
            padding: 60px 0 40px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .report-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,121,0,0.1) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .report-hero h1 {
            font-weight: 800;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: -1px;
            position: relative;
        }

        .report-hero .badge {
            background-color: var(--orange-main) !important;
            color: white;
            font-weight: 600;
            border-radius: 0;
            padding: 8px 15px;
            position: relative;
        }

        .academy-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .academy-header {
            background: linear-gradient(135deg, #ff7900, #ffaa40);
            padding: 20px;
            color: white;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-box {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-box:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .stat-box .value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
            display: block;
        }

        .stat-box .label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .breakdown-section {
            padding: 20px;
            background: #fafafa;
        }

        .breakdown-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .bar-chart {
            margin-bottom: 12px;
        }

        .bar-chart .bar-label {
            font-size: 0.75rem;
            color: #666;
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
        }

        .bar-chart .bar-track {
            height: 12px;
            background: #eee;
            border-radius: 6px;
            overflow: hidden;
        }

        .bar-chart .bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 0.5s ease;
        }

        .bar-fill.male { background: linear-gradient(90deg, #0d6efd, #4dabf7); }
        .bar-fill.female { background: linear-gradient(90deg, #dc3545, #e4606d); }
        .bar-fill.university { background: linear-gradient(90deg, #6c75e1, #8b92f0); }
        .bar-fill.neighborhood { background: linear-gradient(90deg, #ff7900, #ffa040); }
        .bar-fill.age { background: linear-gradient(90deg, #4bb4e6, #74c9f0); }

        .gender-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .gender-box {
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }

        .gender-box.male {
            background: rgba(13, 110, 253, 0.08);
        }

        .gender-box.female {
            background: rgba(220, 53, 69, 0.08);
        }

        .gender-box .value {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .gender-box.male .value { color: #0d6efd; }
        .gender-box.female .value { color: #dc3545; }

        .gender-box .label {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #666;
        }

        .data-card {
            border: none;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .data-card-header {
            background: var(--orange-black);
            color: white;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border: none;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            font-weight: 600;
        }

        .table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 0.85rem;
        }

        .table tbody tr:hover {
            background: #fffbf5;
        }

        .status-pill {
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 4px 12px;
            display: inline-block;
        }

        .status-applied { background: #e9ecef; color: #495057; }
        .status-accepted { background: #fff3cd; color: #856404; }
        .status-enrolled { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }

        .btn-orange {
            background-color: var(--orange-main);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 25px;
            border: none;
        }

        .btn-orange:hover {
            background-color: var(--orange-black);
            color: white;
        }

        @media print {
            .no-print { display: none !important; }
            .academy-card { box-shadow: none; border: 1px solid #ddd; }
            .report-hero { background: #333 !important; }
        }
    </style>
</head>
<body>

    <div class="oca-nav no-print">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="oca-logo-square"></div>
                <span class="text-white fw-bold">ORANGE CODING ACADEMY</span>
            </div>
            <div>
                <button class="btn btn-orange btn-sm" onclick="window.print()">
                    <i class="bi bi-download me-2"></i> Export Report
                </button>
            </div>
        </div>
    </div>

    <header class="report-hero">
        <div class="container text-center position-relative">
            <div class="badge mb-3">ACADEMY ANALYTICS REPORT</div>
            <h1>Academy Performance Data</h1>
            <p class="lead opacity-75">{{ $isSingleAcademy ? $academyReports[0]['academy']['name'] : 'All Jordan Locations' }}</p>
            <div class="mt-4 small opacity-50">Report Generated: {{ $generatedAt }}</div>
        </div>
    </header>

    <div class="container">
        @foreach($academyReports as $report)
        <div class="academy-card">
            <div class="academy-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="fw-bold mb-1" style="font-size: 1.3rem;">{{ $report['academy']['name'] }}</h2>
                        <p class="mb-0 opacity-75"><i class="bi bi-geo-alt-fill me-1"></i> {{ $report['academy']['location'] }}, Jordan</p>
                    </div>
                    <div class="text-end">
                        <div class="stat-box" style="background: rgba(255,255,255,0.2);">
                            <span class="value" style="font-size: 2.5rem;">{{ $report['totalStudents'] }}</span>
                            <span class="label">Total Students</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="breakdown-section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="breakdown-title">
                            <i class="bi bi-gender-ambiguous me-1"></i> Gender Distribution
                        </div>
                        <div class="gender-row">
                            <div class="gender-box male">
                                <div class="value">{{ $report['genderStats']['male'] ?? 0 }}</div>
                                <div class="label"><i class="bi bi-gender-male me-1"></i> Male</div>
                            </div>
                            <div class="gender-box female">
                                <div class="value">{{ $report['genderStats']['female'] ?? 0 }}</div>
                                <div class="label"><i class="bi bi-gender-female me-1"></i> Female</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breakdown-title">
                            <i class="bi bi-bar-chart-fill me-1"></i> Enrollment Status
                        </div>
                        <div class="stat-grid" style="grid-template-columns: repeat(3, 1fr);">
                            <div class="stat-box" style="background: #fff3cd;">
                                <span class="value" style="color: #856404; font-size: 1.3rem;">{{ collect($report['cohortData'])->sum('applied') }}</span>
                                <span class="label" style="color: #856404;">Applied</span>
                            </div>
                            <div class="stat-box" style="background: #d4edda;">
                                <span class="value" style="color: #155724; font-size: 1.3rem;">{{ collect($report['cohortData'])->sum('accepted') }}</span>
                                <span class="label" style="color: #155724;">Accepted</span>
                            </div>
                            <div class="stat-box" style="background: #cce5ff;">
                                <span class="value" style="color: #004085; font-size: 1.3rem;">{{ collect($report['cohortData'])->sum('enrolled') }}</span>
                                <span class="label" style="color: #004085;">Enrolled</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 20px 0;">

                <div class="row">
                    @if(!empty($report['universityStats']))
                    <div class="col-md-6">
                        <div class="breakdown-title">
                            <i class="bi bi-building me-1"></i> Top Universities
                        </div>
                        @php $maxUni = max(array_values($report['universityStats'])); @endphp
                        @foreach(array_slice($report['universityStats'], 0, 5, true) as $uni => $count)
                        <div class="bar-chart">
                            <div class="bar-label">
                                <span>{{ $uni }}</span>
                                <span class="fw-bold">{{ $count }}</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill university" style="width: {{ ($count / $maxUni) * 100 }}%;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if(!empty($report['neighborhoodStats']))
                    <div class="col-md-6">
                        <div class="breakdown-title">
                            <i class="bi bi-geo-alt me-1"></i> Top Neighborhoods
                        </div>
                        @php $maxNb = max(array_values($report['neighborhoodStats'])); @endphp
                        @foreach(array_slice($report['neighborhoodStats'], 0, 5, true) as $nb => $count)
                        <div class="bar-chart">
                            <div class="bar-label">
                                <span>{{ $nb }}</span>
                                <span class="fw-bold">{{ $count }}</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill neighborhood" style="width: {{ ($count / $maxNb) * 100 }}%;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="p-3">
                <div class="data-card">
                    <div class="data-card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-people me-2"></i>Cohort Enrollment Breakdown</span>
                        <span class="badge bg-warning text-dark">{{ count($report['cohortData']) }} Cohorts</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cohort Name</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Applied</th>
                                    <th class="text-center">Accepted</th>
                                    <th class="text-center">Enrolled</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['cohortData'] as $cohortName => $data)
                                <tr>
                                    <td class="fw-bold">{{ $cohortName }}</td>
                                    <td class="text-center">{{ $data['total'] }}</td>
                                    <td class="text-center">{{ $data['applied'] }}</td>
                                    <td class="text-center"><span class="status-pill status-accepted">{{ $data['accepted'] }}</span></td>
                                    <td class="text-center"><span class="status-pill status-enrolled">{{ $data['enrolled'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="data-card">
                    <div class="data-card-header">
                        <i class="bi bi-list-ul me-2"></i>Student Roster
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Education</th>
                                    <th>University</th>
                                    <th>Cohort</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['studentsList']->take(20) as $student)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $student['name'] }}</div>
                                        <div class="small text-muted">{{ $student['email'] }}</div>
                                    </td>
                                    <td>{{ $student['education'] }}</td>
                                    <td>{{ $student['university'] }}</td>
                                    <td>{{ $student['cohort'] }}</td>
                                    <td>
                                        <span class="status-pill status-{{ $student['status'] }}">
                                            {{ $student['status'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($report['studentsList']->count() > 20)
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-three-dots"></i> And {{ $report['studentsList']->count() - 20 }} more students...
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <footer class="mt-5 mb-5 pt-5 border-top text-center">
            <div class="oca-logo-square mb-3" style="width: 40px; height: 40px; margin: 0 auto;"></div>
            <p class="fw-bold mb-1">Orange Coding Academy Jordan</p>
            <p class="text-muted small">Coding is the language of the future.</p>
            <p class="text-muted small">© {{ date('Y') }} Orange. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
