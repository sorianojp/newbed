<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\TeachingAttendance;
use App\Models\TeachingSchedule;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{

    public $attendanceServices;

    public function __construct(AttendanceService $attendanceServices)
    {
        $this->attendanceServices = $attendanceServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('dtr.attendance.index');
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
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'employee_id' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'days' => ['required', 'array'],
        ]);

        $data = [];

        if ($request->filled('end_date')) {
            $first_date = Carbon::parse($request->start_date);
            $last_date = Carbon::parse($request->end_date);

            // Create a collection of all dates in the range
            $dateRange = collect();
            for ($date = $first_date->copy(); $date->lte($last_date); $date->addDay()) {
                if (in_array($date->format('l'), $request->days)) {
                    $dateRange->push($date->copy());
                }
            }

            foreach ($dateRange as $date) {
                $data[] = [
                    'employee_id' => $request->employee_id,
                    'date' => $date->format('Y-m-d'),
                    'time' => $request->start_time,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                if ($request->filled('end_time')) {
                    $data[] = [
                        'employee_id' => $request->employee_id,
                        'date' => $date->format('Y-m-d'),
                        'time' => $request->end_time,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }
        } else {
            $data[] = [
                'employee_id' => $request->employee_id,
                'date' => $request->start_date,
                'time' => $request->start_time,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            if ($request->filled('end_time')) {
                $data[] = [
                    'employee_id' => $request->employee_id,
                    'date' => $request->start_date,
                    'time' => $request->end_time,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        Attendance::insert($data);

        return response()->json(['message' => 'Attendance added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json(['message' => 'Attendance deleted successfully']);
    }

    public function checkerReport(): View
    {
        return view('dtr.attendance.checker');
    }

    public function checkerReportStore(Request $request): JsonResponse
    {
        $attendance = TeachingAttendance::updateOrCreate(
            ['teaching_schedule_id' => $request->teaching_schedule_id, 'date' => $request->date],
            ['status' => $request->status]
        );

        return response()->json(['attendance' => $attendance]);
    }

    public function getAttendances(Request $request): JsonResponse
    {
        $employee = $request->input('employee_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $formattedAttendances = $this->attendanceServices->getAttendances($employee, $startDate, $endDate);

        return response()->json($formattedAttendances->values()->toArray());
    }

    public function getSchedules(Request $request): JsonResponse
    {

        $employee = $request->employee_id;
        $date = Carbon::parse($request->date);

        $attendances = Attendance::where('employee_id', $employee)->where(function ($query) use ($date) {
            $query->whereDate('date', $date->format('Y-m-d'));
        })->orderBy('time')->get();

        $schedules = TeachingSchedule::with([
            'teachingAttendances' => function ($query) use ($date) {
                $query->whereDate('date', $date->format('Y-m-d'))->limit(1);
            }]
        )->where('employee_id', $employee)
            ->where('start_date', '<=', $date->format('Y-m-d'))
            ->where('end_date', '>=', $date->format('Y-m-d'))
            ->where('day_of_week', $date->format('l'))
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'attendances' => $attendances,
            'schedules' => $schedules,
        ]);
    }

}
