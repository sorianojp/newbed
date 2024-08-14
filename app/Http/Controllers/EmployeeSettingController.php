<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSetting;
use App\Models\PayrollType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payrollTypes = PayrollType::all();
        return view('payroll.settings.index', compact('payrollTypes'));
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
            'salary_period' => ['required'],
            'computation_basis' => ['required'],
            'employee_id' => ['required'],
            'payroll_type_id' => ['required'],
            'monthly_basic_salary' => ['nullable'],
            'cut_off_days_per_month' => ['nullable'],
            'hourly_rate' => ['nullable'],
            'teaching_rate' => ['nullable'],
            'tax' => ['nullable'],
            'sss' => ['nullable'],
            'pag_ibig' => ['nullable'],
            'phil_health' => ['nullable'],
            'holiday_pay' => ['nullable'],
        ]);

        $employeeConfiguration = EmployeeSetting::updateOrCreate(
            ['employee_id' => $request->employee_id],
            [
                'salary_period' => $request->salary_period,
                'computation_basis' => $request->computation_basis,
                'payroll_type_id' => $request->payroll_type_id,
                'monthly_basic_salary' => $request->monthly_basic_salary,
                'cut_off_days_per_month' => $request->cut_off_days_per_month,
                'hourly_rate' => $request->hourly_rate,
                'teaching_rate' => $request->teaching_rate,
                'tax' => $request->tax,
                'sss' => $request->sss,
                'pag_ibig' => $request->pag_ibig,
                'phil_health' => $request->phil_health,
                'holiday_pay' => $request->holiday_pay,
            ]);

        return response()->json([
            'configuration' => $employeeConfiguration->load('payrollType'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSetting $employeeSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeSetting $employeeSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeSetting $employeeSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeSetting $employeeSetting)
    {
        //
    }

    public function getEmployeeConfig(Request $request): JsonResponse
    {
        $employee = Employee::find($request->employee_id);

        $employeeConfiguration = $employee->employeeSetting;
        if ($employeeConfiguration) {
            $employeeConfiguration->load('payrollType');
        }

        return response()->json([
            'configuration' => $employeeConfiguration,
        ]);
    }
}
