<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\OvertimeType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dtr.overtime.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $overtimeTypes = OvertimeType::all();
        return view('dtr.overtime.create', compact('overtimeTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response()->json($request->all());
        $request->validate([
            'overtime_type_id' => ['required'],
            'employees' => ['required', 'array'],
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'reason' => ['required'],
        ]);

        $overtime = Overtime::create([
            'overtime_type_id' => $request->overtime_type_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
        ]);

        $overtime->employees()->attach($request->employees);

        return response()->json(['message' => 'Successfully added overtimes']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Overtime $overtime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Overtime $overtime)
    {
        $overtimeTypes = OvertimeType::all();
        $overtime = $overtime->load('employees');
        return view('dtr.overtime.edit', compact('overtime', 'overtimeTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Overtime $overtime)
    {
        // return response()->json($request->all());
        $request->validate([
            'overtime_type_id' => ['required'],
            'employees' => ['required', 'array'],
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'reason' => ['required'],
        ]);

        $overtime->update([
            'overtime_type_id' => $request->overtime_type_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
        ]);

        $overtime->employees()->sync($request->employees);

        return response()->json(['message' => 'Successfully updated overtime']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Overtime $overtime)
    {
        //
    }

    public function getOvertime(Request $request): JsonResponse
    {
        $employee = $request->input('employee_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $overtimes = Overtime::with(['employees'])
            ->where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->where('date', $startDate);
                }
            })
            ->whereHas('employees', function ($query) use ($employee) {
                $query->where('id', $employee);
            })
            ->get();

        return response()->json($overtimes);
    }
}
