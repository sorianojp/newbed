<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSetting;
use App\Models\PayrollSchedule;
use App\Models\PayrollType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payrollSchedules = PayrollSchedule::latest()->get();
        return view('payroll.schedule.index', compact('payrollSchedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ];
        $payrollTypes = PayrollType::all();
        return view('payroll.schedule.create', compact('months', 'payrollTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'payroll_type_id' => ['required'],
            'salary_start_date' => ['required', 'date'],
            'salary_end_date' => ['required', 'date'],
            'cutoff_start_date' => ['required', 'date'],
            'cutoff_end_date' => ['required', 'date'],
            'pay_date' => ['required', 'date'],
            'period' => ['required'],
            'month' => ['required'],
            'year' => ['required'],
        ]);

        PayrollSchedule::create($validatedData);

        return redirect()->route('schedules.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PayrollSchedule $payrollSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollSchedule $payrollSchedule)
    {

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ];
        $payrollTypes = PayrollType::all();

        return view('payroll.schedule.edit', compact('months', 'payrollTypes', 'payrollSchedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PayrollSchedule $payrollSchedule)
    {
        $validatedData = $request->validate([
            'payroll_type_id' => ['required'],
            'salary_start_date' => ['required', 'date'],
            'salary_end_date' => ['required', 'date'],
            'cutoff_start_date' => ['required', 'date'],
            'cutoff_end_date' => ['required', 'date'],
            'pay_date' => ['required', 'date'],
            'period' => ['required'],
            'month' => ['required'],
            'year' => ['required'],
        ]);

        $payrollSchedule->update($validatedData);

        return redirect()->route('schedules.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PayrollSchedule $payrollSchedule)
    {
        //
    }

    public function getSchedules(Request $request)
    {
        // Input month and year
        $month = $request->month;
        $year = $request->year;

        $employeeSetting = EmployeeSetting::where('employee_id', $request->employee)->first();

// Parse the input month and year into Carbon dates
        $startDate = Carbon::parse("first day of $month $year")->format('Y-m-d');
        $endDate = Carbon::parse("last day of $month $year")->format('Y-m-d');

        // Query the payroll_schedule table
        $payrollSchedules = PayrollSchedule::where('payroll_type_id', $employeeSetting->payroll_type_id)->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('salary_start_date', [$startDate, $endDate])
                ->orWhereBetween('salary_end_date', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('salary_start_date', '<=', $startDate)
                        ->where('salary_end_date', '>=', $endDate);
                });
        })->get();

        // // Optional: Check the results
        // if ($payrollSchedules->isEmpty()) {
        //     // Handle the case where no records are found
        //     return response()->json(['message' => 'No payroll schedules found for the given month and year.'], 404);
        // }

        return response()->json($payrollSchedules);
    }
}
