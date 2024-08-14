<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeDeduction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $deductions = Deduction::orderBy('name')->get();
        return view('payroll.settings.deductions.index', compact('deductions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payroll.settings.deductions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required'],
            'name' => ['required'],
            'hidden' => ['nullable'],
            'deducted' => ['nullable'],
        ]);

        Deduction::create([
            'name' => $request->name,
            'code' => $request->code,
            'hidden' => isset($request->hidden) ? true : false,
            'deducted' => isset($request->deducted) ? true : false,
        ]);

        return redirect()->route('deductions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Deduction $deduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deduction $deduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deduction $deduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction)
    {
        //
    }

    public function indexEmployee(): View
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ];
        $deductions = Deduction::orderBy('name')->get();
        return view('payroll.computations.deduction', compact('months', 'deductions'));
    }

    public function storeEmployee(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => ['required'],
            'payroll_schedule_id' => ['required'],
            'deduction_id' => ['required'],
            'amount' => ['required'],
            'remark' => ['nullable'],
        ]);

        $validatedData['encoded_by'] = Auth::id();

        $deduction = EmployeeDeduction::create($validatedData);

        return response()->json([
            'deduction' => $deduction->load('deduction'),
        ]);
    }

    public function getDeductions(Request $request): JsonResponse
    {
        $employee = Employee::findOrFail($request->employee_id);
        $deductions = EmployeeDeduction::with('deduction')->where('employee_id', $employee->id)->where('payroll_schedule_id', $request->payroll_schedule_id)->get();

        return response()->json([
            'deductions' => $deductions,
            'employee' => $employee->load('employeeSetting'),
        ]);

    }
}
