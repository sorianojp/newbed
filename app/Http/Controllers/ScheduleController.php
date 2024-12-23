<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollSchedule;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public $scheduleServices;

    public function __construct(ScheduleService $scheduleServices)
    {
        $this->scheduleServices = $scheduleServices;
    }

    public function index(Request $request): View
    {
        $employees = Employee::all();
        return view('dtr.schedules.index', compact('employees'));
    }

    public function getWorkingSchedules(Request $request): JsonResponse
    {
        $employee = Employee::find($request->employee_id);
        $payrollSchedule = PayrollSchedule::findOrFail($request->schedule_id);

        $schedules = $this->scheduleServices->getWorkingSchedules($employee->id, $payrollSchedule->cutoff_start_date, $payrollSchedule->cutoff_end_date, );

        $payrollType = $employee->employeeSetting->payrollType->name;

        return response()->json(
            $schedules
        );
    }
}
