<?php

namespace App\Http\Controllers;

use App\Models\TeachingSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachingScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dtr.schedules.teaching-schedules');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required'],
            'days' => ['required', 'array'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'effective_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'weight' => ['required', 'numeric'],
        ]);

        foreach ($request->days as $day) {
            TeachingSchedule::create([
                'employee_id' => $request->employee_id,
                'day_of_week' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'start_date' => $request->effective_date,
                'end_date' => $request->end_date,
                'weight' => $request->weight,
            ]);
        }

        return response()->json(['message' => 'Schedule added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(TeachingSchedule $teachingSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeachingSchedule $teachingSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeachingSchedule $teachingSchedule)
    {
        $request->validate([
            'day_of_week' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'effective_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'weight' => ['required', 'numeric'],
        ]);

        $teachingSchedule->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'start_date' => $request->effective_date,
            'end_date' => $request->end_date,
            'weight' => $request->weight,
        ]);

        return response()->json(['message' => 'Schedule successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeachingSchedule $teachingSchedule)
    {
        $teachingSchedule->delete();

        return response()->json(['message' => 'Schedule deleted successfully!']);
    }

    public function getSchedules(Request $request): JsonResponse
    {
        $employee = $request->input('employee_id');

        // Custom ordering for days of the week
        $dayOfWeekOrder = "CASE
                                WHEN day_of_week = 'Monday' THEN 1
                                WHEN day_of_week = 'Tuesday' THEN 2
                                WHEN day_of_week = 'Wednesday' THEN 3
                                WHEN day_of_week = 'Thursday' THEN 4
                                WHEN day_of_week = 'Friday' THEN 5
                                WHEN day_of_week = 'Saturday' THEN 6
                                WHEN day_of_week = 'Sunday' THEN 7
                                END";

        $schedules = TeachingSchedule::select('id', 'employee_id', 'day_of_week', 'start_time', 'end_time', 'start_date', 'end_date', 'weight',
            DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, start_time, end_time) / 60, 2) AS hours'),
            DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, start_time, end_time) / 60 * weight, 2) AS weighted_hours'),
            DB::raw('CONCAT(DATE_FORMAT(start_time, "%h:%i%p"), " - ", DATE_FORMAT(end_time, "%h:%i%p")) AS time_range'))
            ->where('employee_id', $employee)
            ->orderByRaw($dayOfWeekOrder)
            ->orderBy('start_time')
            ->get();

        // Calculate total hours per day
        $totalHoursPerDay = $schedules->groupBy('day_of_week')->map(function ($daySchedules) {
            return number_format($daySchedules->sum('weighted_hours'), 2);

        });

        // Default array for all days of the week with 0 hours
        $defaultHoursPerDay = collect([
            'Monday' => 0.00,
            'Tuesday' => 0.00,
            'Wednesday' => 0.00,
            'Thursday' => 0.00,
            'Friday' => 0.00,
            'Saturday' => 0.00,
            'Sunday' => 0.00,
        ]);

        // Merge the calculated hours with the default array
        $totalHoursPerDay = $defaultHoursPerDay->merge($totalHoursPerDay);
        $totalHours = number_format($totalHoursPerDay->sum(), 2);

        return response()->json([
            'schedules' => $schedules,
            'total_hours_per_day' => $totalHoursPerDay,
            'total_hours' => $totalHours,

        ]);
    }
}
