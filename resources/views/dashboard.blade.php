<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Attendance Management</p>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard</h2>
            </div>
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-7 text-white shadow-lg sm:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm font-medium text-indigo-100">Welcome back, {{ Auth::user()->name }}</p>
                        <h1 class="mt-1 text-2xl font-bold sm:text-3xl">Keep your team on track.</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-100">Monitor today's attendance, identify absences quickly, and keep an eye on employees who are currently working.</p>
                    </div>
                    <div class="rounded-xl bg-white/10 px-5 py-4 ring-1 ring-white/20 backdrop-blur-sm">
                        <p class="text-xs font-medium uppercase tracking-wider text-indigo-100">Today</p>
                        <p class="mt-1 text-lg font-semibold">{{ now()->format('M d, Y') }}</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Attendance overview">
                @php
                    $stats = [
                        ['label' => 'Total Employees', 'value' => $employeeCount, 'hint' => 'Registered employees', 'icon' => 'users'],
                        ['label' => 'Present Today', 'value' => $presentCount, 'hint' => 'Checked in today', 'icon' => 'check'],
                        ['label' => 'Currently Working', 'value' => $workingCount, 'hint' => 'No checkout yet', 'icon' => 'clock'],
                        ['label' => 'Absent Today', 'value' => $absentCount, 'hint' => 'No attendance recorded', 'icon' => 'calendar'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $stat['value'] }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $stat['hint'] }}</p>
                            </div>
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600" aria-hidden="true">
                                @if ($stat['icon'] === 'users')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm6-3a3 3 0 110-6m4 15v-2a4 4 0 00-3-3.87" /></svg>
                                @elseif ($stat['icon'] === 'check')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" /></svg>
                                @elseif ($stat['icon'] === 'clock')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="1.8" /><path stroke-linecap="round" stroke-width="1.8" d="M12 7v5l3 2" /></svg>
                                @else
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" /></svg>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-gray-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Today's Attendance</h2>
                        <p class="mt-1 text-sm text-gray-500">A quick overview of today's check-ins and check-outs.</p>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                        {{ $todayAttendances->count() }} records
                    </span>
                </div>

                @if ($todayAttendances->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" /></svg>
                        </div>
                        <h3 class="mt-4 text-sm font-semibold text-gray-900">No attendance recorded</h3>
                        <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500">There are no attendance records for today yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Check in</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Check out</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($todayAttendances as $attendance)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-50 text-sm font-semibold text-indigo-600">
                                                    {{ strtoupper(substr($attendance->user?->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $attendance->user?->name ?? 'Unknown employee' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $attendance->attendance_date }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{{ $attendance->start_time ?? '—' }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{{ $attendance->end_time ?? '—' }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if (is_null($attendance->end_time))
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Working</span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700"><span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>Completed</span>
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
