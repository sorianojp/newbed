<?php

namespace App\Http\Controllers;

use App\Models\RegularSchedule;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegularScheduleController extends Controller
{
    public $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dtr.schedules.regular-schedules');
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
        ]);

        foreach ($request->days as $day) {
            RegularSchedule::create([
                'employee_id' => $request->employee_id,
                'day_of_week' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'start_date' => $request->effective_date,
            ]);
        }

        return response()->json(['message' => 'Schedule added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(RegularSchedule $regularSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegularSchedule $regularSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegularSchedule $regularSchedule)
    {
        $request->validate([
            'day_of_week' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'effective_date' => ['required', 'date'],
        ]);

        $regularSchedule->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'start_date' => $request->effective_date,
        ]);

        return response()->json(['message' => 'Schedule successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegularSchedule $regularSchedule)
    {
        $regularSchedule->delete();

        return response()->json(['message' => 'Schedule deleted successfully!']);
    }

    public function getSchedules(Request $request): JsonResponse
    {
        $employee = $request->input('employee_id');

        return response()->json(
            $this->scheduleService->getRegularSchedules($employee),
        );
    }
}
