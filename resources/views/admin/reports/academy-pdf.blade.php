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

        /* Top Navigation Bar */
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

        /* Branding Header */
        .report-hero {
            background-color: var(--orange-black);
            color: var(--orange-white);
            padding: 60px 0 40px 0;
            margin-bottom: 40px;
        }

        .report-hero h1 {
            font-weight: 800;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .report-hero .badge {
            background-color: var(--orange-main) !important;
            color: white;
            font-weight: 600;
            border-radius: 0;
            padding: 8px 15px;
        }

        /* Stats Dashboard Widgets */
        .stat-widget {
            background-color: var(--orange-grey);
            border: none;
            border-left: 5px solid var(--orange-main);
            padding: 25px;
            height: 100%;
            transition: all 0.3s ease;
        }

        .stat-widget:hover {
            background-color: var(--orange-black);
            color: white;
            transform: translateY(-5px);
        }

        .stat-widget .value {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            display: block;
            margin-bottom: 5px;
        }

        .stat-widget .label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: inherit;
            opacity: 0.7;
        }

        /* Content Cards */
        .data-card {
            border: 1px solid #e0e0e0;
            border-radius: 0;
            margin-bottom: 30px;
        }

        .data-card-header {
            background-color: var(--orange-black);
            color: white;
            padding: 15px 20px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        /* Table Styling */
        .table thead {
            background-color: var(--orange-grey);
        }

        .table thead th {
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 15px 20px;
        }

        .table tbody td {
            padding: 12px 20px;
            border-bottom: 1px solid #eeeeee;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #fff9f4;
        }

        /* Orange Status Badges */
        .status-pill {
            border-radius: 0;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 5px 10px;
            display: inline-block;
        }

        .status-applied { background: #E0E0E0; color: #333; }
        .status-accepted { background: #FF7900; color: #fff; }
        .status-enrolled { background: #000000; color: #fff; }
        .status-graduated { border: 1px solid var(--orange-main); color: var(--orange-main); }

        /* Print Settings */
        @media print {
            .no-print { display: none !important; }
            .report-hero { background: white !important; color: black !important; border-bottom: 2px solid #000; padding: 20px 0; }
            .stat-widget { border: 1px solid #ddd !important; background: white !important; color: black !important; }
            .data-card-header { background: #333 !important; }
        }

        .btn-orange {
            background-color: var(--orange-main);
            color: white;
            border-radius: 0;
            font-weight: 700;
            text-transform: uppercase;
            padding: 10px 25px;
            border: none;
        }

        .btn-orange:hover {
            background-color: var(--orange-black);
            color: white;
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
        <div class="container text-center">
            <div class="badge mb-3">INTERNAL REPORT</div>
            <h1>Academy Performance Data</h1>
            <p class="lead opacity-75">{{ $isSingleAcademy ? $academyReports[0]['academy']['name'] : 'All Jordan Locations' }}</p>
            <div class="mt-4 small opacity-50">Report Generated: {{ $generatedAt }}</div>
        </div>
    </header>

    <div class="container">
        @foreach($academyReports as $report)
        <div class="academy-section {{ !$loop->last ? 'mb-5 pb-5 border-bottom' : '' }}">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h2 class="fw-800 text-uppercase mb-1" style="font-weight: 800;">{{ $report['academy']['name'] }}</h2>
                    <p class="text-muted mb-0"><i class="bi bi-geo-alt-fill text-orange"></i> {{ $report['academy']['location'] }}, Jordan</p>
                </div>
            </div>

            <div class="row g-3 mb-5">
                <div class="col-6 col-lg-2">
                    <div class="stat-widget">
                        <span class="value">{{ $report['totalStudents'] }}</span>
                        <span class="label">Students</span>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="stat-widget">
                        <span class="value">{{ $report['genderStats']['male'] ?? 0 }}</span>
                        <span class="label">Male</span>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="stat-widget">
                        <span class="value">{{ $report['genderStats']['female'] ?? 0 }}</span>
                        <span class="label">Female</span>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="stat-widget">
                        <span class="value text-orange">{{ count($report['cohortData']) }}</span>
                        <span class="label">Cohorts</span>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="stat-widget">
                        <span class="value">{{ collect($report['cohortData'])->sum('applied') }}</span>
                        <span class="label">Applied</span>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="stat-widget">
                        <span class="value">{{ collect($report['cohortData'])->sum('accepted') }}</span>
                        <span class="label">Accepted</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-4">
                    <div class="data-card">
                        <div class="data-card-header d-flex justify-content-between">
                            <span>Cohort Enrollment Breakdown</span>
                            <i class="bi bi-bar-chart-fill"></i>
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
                                        <td class="text-center text-orange fw-bold">{{ $data['accepted'] }}</td>
                                        <td class="text-center"><span class="status-pill status-enrolled">{{ $data['enrolled'] }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            Academy Roster - Detailed View
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Education</th>
                                        <th>University</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report['studentsList'] as $student)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $student['name'] }}</div>
                                            <div class="small text-muted">{{ $student['email'] }}</div>
                                        </td>
                                        <td class="small">{{ $student['education'] }}</td>
                                        <td class="small">{{ $student['university'] }}</td>
                                        <td>
                                            @php
                                                $statusStyle = 'status-' . strtolower($student['status']);
                                            @endphp
                                            <span class="status-pill {{ $statusStyle }}">
                                                {{ $student['status'] }}
                                            </span>
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
        @endforeach

        <footer class="mt-5 mb-5 pt-5 border-top">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <div class="oca-logo-square mb-2" style="width: 30px; height: 30px;"></div>
                    <p class="fw-bold mb-0">Orange Coding Academy Jordan</p>
                    <p class="text-muted small">Coding is the language of the future.</p>
                </div>
                <div class="col-md-6 text-center text-md-end pt-4">
                    <p class="text-muted small">© {{ date('Y') }} Orange. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>