<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academy Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page-break { page-break-after: always; }
        }
        
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .no-print .btn {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            padding: 25px 30px;
            margin-bottom: 25px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 13px;
            opacity: 0.9;
        }
        
        .header .meta {
            font-size: 10px;
            opacity: 0.8;
            margin-top: 10px;
        }
        
        .academy-section {
            margin-bottom: 35px;
            page-break-after: always;
        }
        
        .academy-section:last-child {
            page-break-after: avoid;
        }
        
        .academy-header {
            background: #f8f9fa;
            border-left: 4px solid #ff7900;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .academy-header h2 {
            font-size: 18px;
            color: #1a1a2e;
            margin-bottom: 5px;
        }
        
        .academy-header .location {
            font-size: 11px;
            color: #666;
        }
        
        .stats-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stat-box {
            flex: 1;
            min-width: 120px;
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-box .number {
            font-size: 28px;
            font-weight: 700;
            color: #ff7900;
        }
        
        .stat-box .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 3px;
        }
        
        .charts-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .chart-container {
            flex: 1;
            min-width: 250px;
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 15px;
        }
        
        .chart-container h4 {
            font-size: 12px;
            color: #1a1a2e;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .chart-box {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .chart-box table {
            width: 100%;
            font-size: 10px;
        }
        
        .chart-box table th {
            background: #f8f9fa;
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
        }
        
        .chart-box table td {
            padding: 5px 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .color-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .table-container {
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .table-container h4 {
            font-size: 12px;
            color: #1a1a2e;
            padding: 12px 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #e5e5e5;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        table th {
            background: #f8f9fa;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            color: #1a1a2e;
            border-bottom: 2px solid #e5e5e5;
        }
        
        table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        table tr:hover {
            background: #fafafa;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        
        .badge-applied { background: #fff3cd; color: #856404; }
        .badge-accepted { background: #d4edda; color: #155724; }
        .badge-rejected { background: #f8d7da; color: #721c24; }
        .badge-enrolled { background: #cce5ff; color: #004085; }
        .badge-graduated { background: #d1ecf1; color: #0c5460; }
        .badge-dropped { background: #e2e3e5; color: #383d41; }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Print / Save as PDF
        </button>
        <button class="btn btn-secondary ms-2" onclick="window.close()">
            <i class="bi bi-x-lg me-2"></i> Close
        </button>
    </div>

    <div class="header">
        <h1>Orange Academy Report</h1>
        <div class="subtitle">{{ $isSingleAcademy ? $academyReports[0]['academy']['name'] : 'All Academies Overview' }}</div>
        <div class="meta">Generated on: {{ $generatedAt }}</div>
    </div>

    @foreach($academyReports as $report)
    <div class="academy-section">
        @if(!$isSingleAcademy)
        <div class="academy-header">
            <h2>{{ $report['academy']['name'] }}</h2>
            <div class="location">{{ $report['academy']['location'] }}</div>
        </div>
        @endif

        <div class="stats-row">
            <div class="stat-box">
                <div class="number">{{ $report['totalStudents'] }}</div>
                <div class="label">Total Students</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $report['genderStats']['male'] ?? $report['genderStats']['Male'] ?? 0 }}</div>
                <div class="label">Male</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $report['genderStats']['female'] ?? $report['genderStats']['Female'] ?? 0 }}</div>
                <div class="label">Female</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ count($report['cohortData']) }}</div>
                <div class="label">Cohorts</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ collect($report['cohortData'])->sum('applied') }}</div>
                <div class="label">Applied</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ collect($report['cohortData'])->sum('accepted') }}</div>
                <div class="label">Accepted</div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-container">
                <h4>Gender Distribution</h4>
                <div class="chart-box">
                    @if(count($report['genderStats']) > 0)
                    <table>
                        @foreach($report['genderStats'] as $gender => $count)
                        @php $dotColor = (strtolower($gender) == 'male') ? '#2C8BFF' : '#FF69B4'; @endphp
                        <tr>
                            <td>
                                <span class="color-dot" style="background: {{ $dotColor }}"></span>
                                {{ ucfirst($gender) }}
                            </td>
                            <td style="text-align: right; font-weight: 600;">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    <div class="no-data">No data available</div>
                    @endif
                </div>
            </div>

            <div class="chart-container">
                <h4>Education Level</h4>
                <div class="chart-box">
                    @if(count($report['educationStats']) > 0)
                    <table>
                        @foreach($report['educationStats'] as $level => $count)
                        <tr>
                            <td>{{ $level }}</td>
                            <td style="text-align: right; font-weight: 600;">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    <div class="no-data">No data available</div>
                    @endif
                </div>
            </div>

            <div class="chart-container">
                <h4>University Distribution</h4>
                <div class="chart-box">
                    @if(count($report['universityStats']) > 0)
                    <table>
                        @foreach($report['universityStats'] as $university => $count)
                        <tr>
                            <td>{{ mb_substr($university, 0, 25) }}</td>
                            <td style="text-align: right; font-weight: 600;">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    <div class="no-data">No data available</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="table-container">
            <h4>Cohort Details</h4>
            @if(count($report['cohortData']) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Cohort Name</th>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: center;">Applied</th>
                        <th style="text-align: center;">Accepted</th>
                        <th style="text-align: center;">Rejected</th>
                        <th style="text-align: center;">Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['cohortData'] as $cohortName => $data)
                    <tr>
                        <td><strong>{{ $cohortName }}</strong></td>
                        <td style="text-align: center;">{{ $data['total'] }}</td>
                        <td style="text-align: center;"><span class="badge badge-applied">{{ $data['applied'] }}</span></td>
                        <td style="text-align: center;"><span class="badge badge-accepted">{{ $data['accepted'] }}</span></td>
                        <td style="text-align: center;"><span class="badge badge-rejected">{{ $data['rejected'] }}</span></td>
                        <td style="text-align: center;"><span class="badge badge-enrolled">{{ $data['enrolled'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-data">No cohort data available</div>
            @endif
        </div>

        <div class="table-container">
            <h4>Students List</h4>
            @if($report['studentsList']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Education</th>
                        <th>University</th>
                        <th>Cohort</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['studentsList'] as $student)
                    <tr>
                        <td>{{ $student['name'] }}</td>
                        <td>{{ $student['email'] }}</td>
                        <td>{{ $student['gender'] }}</td>
                        <td>{{ mb_substr($student['education'], 0, 15) }}</td>
                        <td>{{ mb_substr($student['university'], 0, 15) }}</td>
                        <td>{{ mb_substr($student['cohort'], 0, 15) }}</td>
                        <td>
                            @switch($student['status'])
                                @case('applied')
                                    <span class="badge badge-applied">Applied</span>
                                    @break
                                @case('accepted')
                                    <span class="badge badge-accepted">Accepted</span>
                                    @break
                                @case('rejected')
                                    <span class="badge badge-rejected">Rejected</span>
                                    @break
                                @case('enrolled')
                                    <span class="badge badge-enrolled">Enrolled</span>
                                    @break
                                @case('graduated')
                                    <span class="badge badge-graduated">Graduated</span>
                                    @break
                                @case('dropped')
                                    <span class="badge badge-dropped">Dropped</span>
                                    @break
                                @default
                                    {{ $student['status'] }}
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-data">No students enrolled</div>
            @endif
        </div>
    </div>
    @endforeach

    <div class="footer">
        <p>Orange Academy Registration System &copy; {{ date('Y') }}</p>
        <p>This is a computer-generated document. No signature is required.</p>
    </div>
</body>
</html>
