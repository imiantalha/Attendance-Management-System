<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-900">Dashboard</h2>
                <p class="mt-1 text-sm text-gray-500">Attendance overview for {{ now()->format('l, d M Y') }}.</p>
            </div>
            @can('create', App\Models\Attendance::class)
                <a href="{{ route('attendances.create') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                    Record Attendance
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-gray-900 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-gray-300">Welcome back</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Keep your team attendance on track.</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-300">Monitor today's attendance, identify employees currently working, and quickly record missing entries.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'Total Employees', 'value' => $employeeCount, 'hint' => 'Active workforce'],
                    ['label' => 'Present Today', 'value' => $presentCount, 'hint' => 'Employees with attendance'],
                    ['label' => 'Currently Working', 'value' => $workingCount, 'hint' => 'Open attendance'],
                    ['label' => 'Absent Today', 'value' => $absentCount, 'hint' => 'No attendance recorded'],
                ] as $metric)
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">{{ $metric['label'] }}</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900">{{ $metric['value'] }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $metric['hint'] }}</p>
                    </div>
                @endforeach
            </div>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Today's Attendance</h3>
                        <p class="mt-1 text-sm text-gray-500">Latest attendance records for today.</p>
                    </div>
                    @can('viewAny', App\Models\Attendance::class)
                        <a href="{{ route('attendances.index') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">View all</a>
                    @endcan
                </div>

                @if ($todayAttendances->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <h4 class="text-sm font-semibold text-gray-900">No attendance recorded yet</h4>
                        <p class="mt-1 text-sm text-gray-500">Today's attendance entries will appear here.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-5 py-3 font-semibold">Employee</th>
                                    <th class="px-5 py-3 font-semibold">Check in</th>
                                    <th class="px-5 py-3 font-semibold">Check out</th>
                                    <th class="px-5 py-3 font-semibold">Duration</th>
                                    <th class="px-5 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($todayAttendances as $attendance)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-5 py-4">
                                            <div class="font-medium text-gray-900">{{ $attendance->user?->name ?? 'Unknown employee' }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->user?->email }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $attendance->start_time ?? '—' }}</td>
                                        <td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $attendance->end_time ?? '—' }}</td>
                                        <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-700">{{ $attendance->working_duration }}</td>
                                        <td class="whitespace-nowrap px-5 py-4">
                                            @if ($attendance->end_time)
                                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">Completed</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Working</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
