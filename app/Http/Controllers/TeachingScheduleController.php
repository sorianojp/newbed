<?php

namespace App\Http\Controllers;

use App\Models\TeachingSchedule;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeachingScheduleController extends Controller
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

        return response()->json(
            $this->scheduleService->getTeachingSchedules($employee),
        );
    }
}
