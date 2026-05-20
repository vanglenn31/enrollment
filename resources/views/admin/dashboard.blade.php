<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- HEADER -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto w-full">

                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome back, Admin!</h1>
                            <p class="text-sm text-gray-500 mt-1">Here's what's happening today.</p>
                        </div>

                        <a href="{{ route('admin.report') }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            Generate Report
                        </a>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        @foreach([
                            ['title' => 'Total Students',    'value' => $studentCount ?? 0],
                            ['title' => 'New Applications',  'value' => $newStudentsThisMonth ?? 0],
                            ['title' => 'Pending Reviews',   'value' => $pendingReviews ?? 0],
                            ['title' => 'Active Programs',   'value' => $activePrograms ?? 0],
                        ] as $card)
                        <div class="bg-white p-4 rounded-xl shadow-sm border">
                            <p class="text-sm text-gray-500">{{ $card['title'] }}</p>
                            <h2 class="text-2xl font-bold text-gray-800 mt-1 text-end">
                                {{ $card['value'] }}
                            </h2>
                        </div>
                        @endforeach
                    </div>

                    <!-- Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                        <!-- Enrollments by Department (Pie Chart) -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border lg:col-span-2">
                            <h2 class="text-sm font-semibold text-gray-700 mb-1">
                                Enrollments by Department
                            </h2>
                            <p class="text-xs text-gray-400 mb-4">
                                From <code class="bg-gray-100 px-1 rounded">v_enrollment_by_department</code>
                            </p>

                            @if($enrollmentByDepartment->isEmpty())
                                <div class="h-64 flex items-center justify-center text-gray-400 text-sm">
                                    No enrollment data yet.
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row items-center gap-6">
                                    <div class="w-56 h-56 flex-shrink-0">
                                        <canvas id="deptPieChart"></canvas>
                                    </div>
                                    <ul id="deptLegend" class="space-y-2 w-full">
                                        @foreach($enrollmentByDepartment as $row)
                                        <li class="flex items-center justify-between text-sm">
                                            <span class="flex items-center gap-2">
                                                <span class="inline-block w-3 h-3 rounded-full dept-dot" data-index="{{ $loop->index }}"></span>
                                                <span class="text-gray-700">{{ $row->department_name }}</span>
                                            </span>
                                            <span class="font-semibold text-gray-800">{{ $row->enrolled_students }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @php
                                    $deptLabels = $enrollmentByDepartment->pluck('department_name');
                                    $deptValues = $enrollmentByDepartment->pluck('enrolled_students');
                                @endphp
                                <script>
                                    window.__deptLabels = @json($deptLabels);
                                    window.__deptValues = @json($deptValues);
                                </script>
                            @endif
                        </div>

                        <!-- Students by Program (paginated) -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border flex flex-col">
                            <div class="mb-1">
                                <h2 class="text-sm font-semibold text-gray-700">Students by Program</h2>
                                <p class="text-xs text-gray-400">
                                    From <code class="bg-gray-100 px-1 rounded">v_students_by_program</code>
                                </p>
                            </div>

                            <div class="flex-1 space-y-2 mt-3">
                                @forelse($studentsByProgram as $program)
                                <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 leading-tight">
                                            {{ $program->program_name }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $program->department_name ?? 'General' }}
                                            &middot; {{ $program->program_code }}
                                        </p>
                                    </div>
                                    <span class="ml-2 bg-blue-50 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        {{ $program->student_count }}
                                    </span>
                                </div>
                                @empty
                                <p class="text-sm text-gray-400 text-center py-6">No programs available.</p>
                                @endforelse
                            </div>

                            @if($studentsByProgram->hasPages())
                            <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                                <span>
                                    {{ $studentsByProgram->firstItem() }}–{{ $studentsByProgram->lastItem() }}
                                    of {{ $studentsByProgram->total() }}
                                </span>
                                <div class="flex gap-1">
                                    @if($studentsByProgram->onFirstPage())
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-300 cursor-not-allowed">&laquo;</span>
                                    @else
                                        <a href="{{ $studentsByProgram->previousPageUrl() }}"
                                           class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">&laquo;</a>
                                    @endif
                                    @if($studentsByProgram->hasMorePages())
                                        <a href="{{ $studentsByProgram->nextPageUrl() }}"
                                           class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">&raquo;</a>
                                    @else
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-300 cursor-not-allowed">&raquo;</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
                    <script>
                    (function () {
                        const canvas = document.getElementById('deptPieChart');
                        if (!canvas || !window.__deptLabels) return;

                        const palette = [
                            '#3B82F6','#10B981','#F59E0B','#EF4444',
                            '#8B5CF6','#EC4899','#06B6D4','#84CC16',
                            '#F97316','#6366F1',
                        ];
                        const colors = window.__deptLabels.map((_, i) => palette[i % palette.length]);

                        document.querySelectorAll('.dept-dot').forEach(dot => {
                            dot.style.backgroundColor = colors[parseInt(dot.dataset.index)] || '#ccc';
                        });

                        new Chart(canvas, {
                            type: 'doughnut',
                            data: {
                                labels: window.__deptLabels,
                                datasets: [{
                                    data: window.__deptValues,
                                    backgroundColor: colors,
                                    borderWidth: 2,
                                    borderColor: '#fff',
                                    hoverOffset: 6,
                                }],
                            },
                            options: {
                                cutout: '62%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => ` ${ctx.parsed} enrolled`,
                                        },
                                    },
                                },
                            },
                        });
                    })();
                    </script>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>