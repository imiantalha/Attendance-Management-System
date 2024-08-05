<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $attendances = Attendance::with(['user','attendedBy'])->latest()->paginate(5);
        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::get();
        return view('attendances.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'=> 'nullable|date_format:H:i',
            // 'attendance_date' => 'required|date_format:d-m-Y',
            'user_id' => 'required|exists:users,id',
            'attendance_by' => 'required|exists:users,id',
        ]);

        // dd($request->all);

        Attendance::create($request->all());

        return redirect()->route('attendances.index')
                ->with('success', 'Attendance created Successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('attendances.show', compact('attendance'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::get();
        $attendance = Attendance::findOrFail($id);
        return view('attendances.edit', compact('attendance', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $startTime .= ':00';
        if ($endTime) {
            $endTime .= ':00';
        }

        // Validate the modified input
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            // 'attendance_date' => 'required|date_format:d-m-Y',
            'user_id' => 'required|exists:users,id',
            'attendance_by' => 'required|exists:users,id',
        ]);

        // Find the attendance record
        $attendance = Attendance::findOrFail($id);

        // Update the attendance record
        $attendance->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
            'attendance_date' => $request->input('attendance_date'),
            'user_id' => $request->input('user_id'),
            'attendance_by' => $request->input('attendance_by'),
        ]);

        return redirect()->route('attendances.index')
                        ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
    
        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance deleted successfully.');
    }
    

    public function report(string $id) 
    {   
        $attendances = Attendance::with(['user','attendedBy'])->where('user_id', $id)->get();
        return view('attendances.report', compact('attendances'));
    }

    public function weeklyReport(string $id)
    {
        Carbon::setLocale('en');
        $monday = Carbon::now()->startOfWeek()->toDateString();
        $currentDay = Carbon::now()->toDateString();
        
        // dd($monday, $currentDay);

        $attendances = Attendance::with(['user', 'attendedBy'])
            ->where('user_id', $id)
            ->whereBetween('attendance_date', [$monday, $currentDay])
            ->get();

        $totalWorkingMinutes = 0;
    
        foreach ($attendances as $attendance) {
            $startTime = Carbon::parse($attendance->start_time);
            $endTime = Carbon::parse($attendance->end_time);
    
            if ($endTime->format('H:i') == '00:00') {
                $endTime->addDay();
            }
    
            $oneDayMinutes = $startTime->diffInMinutes($endTime); 
            $totalWorkingMinutes += $oneDayMinutes;
        }
    
        return view('attendances.week-report', compact('attendances', 'totalWorkingMinutes'));
    }

    public function monthlyReport(string $id)
    {
        $firstDay = Carbon::now()->startOfMonth();
        $currentDay = Carbon::now();

        $attendances = Attendance::with(['user', 'attendedBy'])
            ->where('user_id', $id)
            ->whereBetween('attendance_date', [$firstDay, $currentDay])
            ->get();

        $totalWorkingMinutes = 0;

        foreach ($attendances as $attendance) {
            $startTime = Carbon::parse($attendance->start_time);
            $endTime = Carbon::parse($attendance->end_time);

            if ($endTime->format('H:i') == '00:00') {
                $endTime->addDay();
            }

            $oneDayMinutes = $startTime->diffInMinutes($endTime); 
            $totalWorkingMinutes += $oneDayMinutes;
        }

        return view('attendances.week-report', compact('attendances', 'totalWorkingMinutes'));
    }

    public function yearlyReport(string $id) 
    {
        $startOfYear = Carbon::now()->startOfYear();
        $currentDay = Carbon::now();

        $attendances = Attendance::with(['user', 'attendedBy'])
        ->where('user_id', $id)
        ->whereBetween('attendance_date', [$startOfYear, $currentDay])
        ->get();

        $totalWorkingMinutes = 0;

        foreach($attendances as $attendance) {
            $startTime = Carbon::parse(($attendance->start_time));
            $endTime = Carbon::parse($attendance->end_time);

            $oneDayMinutes = $startTime->diffInMinutes($endTime);
            $totalWorkingMinutes += $oneDayMinutes;
        }

        return view('attendances.week-report', compact('attendances', 'totalWorkingMinutes'));
    }
}
 