<?php

namespace App\Http\Controllers;

use App\Models\Additional;
use App\Models\Employee;
use App\Models\EmployeeAdditional;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdditionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $additionals = Additional::orderBy('name')->get();
        return view('payroll.settings.additionals.index', compact('additionals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payroll.settings.additionals.create');
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
            'included' => ['nullable'],
        ]);

        Additional::create([
            'name' => $request->name,
            'code' => $request->code,
            'hidden' => isset($request->hidden) ? true : false,
            'included' => isset($request->included) ? true : false,
        ]);

        return redirect()->route('additionals.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Additional $additional)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Additional $additional)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Additional $additional)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Additional $additional)
    {
        //
    }

    public function indexEmployee(): View
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ];
        $additionals = Additional::orderBy('name')->get();
        return view('payroll.computations.additional', compact('months', 'additionals'));
    }

    public function storeEmployee(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => ['required'],
            'payroll_schedule_id' => ['required'],
            'additional_id' => ['required'],
            'amount' => ['required'],
            'remark' => ['nullable'],
        ]);

        $validatedData['encoded_by'] = Auth::id();

        $additional = EmployeeAdditional::create($validatedData);

        return response()->json([
            'additional' => $additional->load('additional'),
        ]);
    }

    public function getAdditionals(Request $request): JsonResponse
    {
        $employee = Employee::findOrFail($request->employee_id);
        $additionals = EmployeeAdditional::with('additional')->where('employee_id', $employee->id)->where('payroll_schedule_id', $request->payroll_schedule_id)->get();

        return response()->json([
            'additionals' => $additionals,
            'employee' => $employee->load('employeeSetting'),
        ]);

    }
}
