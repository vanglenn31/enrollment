<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Report – {{ now()->format('F d, Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1f2937;
            background: #fff;
            padding: 40px;
        }

        .report-header {
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 16px;
            margin-bottom: 28px;
        }
        .report-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1e3a5f;
        }
        .report-header p {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        .section { margin-bottom: 28px; }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-left: 4px solid #3b82f6;
            padding-left: 8px;
            margin-bottom: 10px;
        }

        /* stat row */
        .stat-row { width: 100%; }
        .stat-row td {
            width: 25%;
            padding: 4px;
            vertical-align: top;
        }
        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
        }
        .stat-card .label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .stat-card .value {
            font-size: 22px;
            font-weight: 700;
            color: #1e3a5f;
            margin-top: 2px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        table.data-table thead tr {
            background: #1e3a5f;
            color: #fff;
        }
        table.data-table thead th {
            padding: 7px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        table.data-table tbody tr:nth-child(even) { background: #f8fafc; }
        table.data-table tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #374151;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .report-footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #9ca3af;
        }
        .footer-left  { float: left; }
        .footer-right { float: right; }
        .clearfix::after { content: ''; display: table; clear: both; }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="report-header">
        <h1>Admin Summary Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }} &nbsp;&bull;&nbsp; Prepared by System Administrator</p>
    </div>

    <!-- OVERVIEW STATS -->
    <div class="section">
        <div class="section-title">Overview</div>
        <table class="stat-row">
            <tr>
                <td>
                    <div class="stat-card">
                        <div class="label">Total Students</div>
                        <div class="value">{{ $studentCount }}</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card">
                        <div class="label">New This Month</div>
                        <div class="value">{{ $newStudentsThisMonth }}</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card">
                        <div class="label">Pending Reviews</div>
                        <div class="value">{{ $pendingReviews }}</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card">
                        <div class="label">Active Programs</div>
                        <div class="value">{{ $activePrograms }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- STUDENT STATUS BREAKDOWN -->
    <div class="section">
        <div class="section-title">Student Status Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-right">Count</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-green">Verified</span></td>
                    <td class="text-right">{{ $verifiedCount }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-yellow">Unverified / Pending</span></td>
                    <td class="text-right">{{ $unverifiedCount }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-red">Withdrawn</span></td>
                    <td class="text-right">{{ $withdrawnCount }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ENROLLMENT BY DEPARTMENT -->
    @if($enrollmentByDepartment->isNotEmpty())
    <div class="section">
        <div class="section-title">Enrollment by Department</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department</th>
                    <th class="text-right">Enrolled Students</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollmentByDepartment as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->department_name }}</td>
                    <td class="text-right"><span class="badge badge-blue">{{ $row->enrolled_students }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- STUDENTS BY PROGRAM -->
    @if($studentsByProgram->isNotEmpty())
    <div class="section">
        <div class="section-title">Students by Program</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program</th>
                    <th>Code</th>
                    <th>Department</th>
                    <th class="text-right">Students</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentsByProgram as $i => $program)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $program->program_name }}</td>
                    <td>{{ $program->program_code }}</td>
                    <td>{{ $program->department_name ?? 'General' }}</td>
                    <td class="text-right"><span class="badge badge-blue">{{ $program->student_count }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- DEPARTMENTS -->
    @if($departments->isNotEmpty())
    <div class="section">
        <div class="section-title">Departments</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th class="text-center">Programs</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $i => $dept)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $dept->name }}</td>
                    <td class="text-center">{{ $dept->programs_count }}</td>
                    <td class="text-center">
                        <span class="badge {{ $dept->status === 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ ucfirst($dept->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- FACULTY SUMMARY -->
    <div class="section">
        <div class="section-title">Faculty Summary</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-right">Count</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-green">Active Professors</span></td>
                    <td class="text-right">{{ $activeProfessors }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-red">Inactive Professors</span></td>
                    <td class="text-right">{{ $inactiveProfessors }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="report-footer clearfix">
        <span class="footer-left">Confidential &bull; For internal use only</span>
        <span class="footer-right">{{ config('app.name') }} &bull; {{ now()->format('Y') }}</span>
    </div>

</body>
</html>
