<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
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
        ]);

        $data = [];

        if ($request->filled('end_date')) {
            $first_date = Carbon::parse($request->start_date);
            $last_date = Carbon::parse($request->end_date);

            // Create a collection of all dates in the range
            $dateRange = collect();
            for ($date = $first_date->copy(); $date->lte($last_date); $date->addDay()) {
                $dateRange->push($date->copy());
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

    public function getAttendances(Request $request): JsonResponse
    {
        $employee = $request->input('employee_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $attendances = Attendance::where('employee_id', $employee)->where(function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('date', $startDate);
            }
        })->orderBy('date')->orderBy('time')->get();

        $formattedAttendances = $attendances->groupBy(function ($attendance) {
            return $attendance->date;
        })->map(function ($group, $date) {
            return [
                'date' => Carbon::parse($date)->format('F j, Y, l'),
                'attendances' => $group->map(function ($attendance) {
                    return $attendance;
                })->toArray(),
            ];
        })->values()->toArray();

        return response()->json($formattedAttendances);
    }
}
