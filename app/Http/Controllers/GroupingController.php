<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Grouping;
use App\Models\PayrollType;
use App\Models\Employee;
use Illuminate\Http\Request;

class GroupingController extends Controller
{
    public function index(): View
    {
        $groupings = Grouping::all();
        return view('groupings.index', compact('groupings'));
    }
    public function create(): View
    {
        $payrollTypes = PayrollType::all();
        return view('groupings.create', compact('payrollTypes'));
    }
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'payroll_type_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        Grouping::create($request->all());
        return redirect()->route('groupings.index')->with('success', 'Grouping Added!');
    }
    public function show($id)
    {
        $grouping = Grouping::findOrFail($id);
        $employees = Employee::all();
        return view('groupings.show', compact('grouping', 'employees'));
    }
    public function addEmployees(Request $request, $id)
    {
        $grouping = Grouping::findOrFail($id);
        $grouping->employees()->sync($request->input('employees', []));
        return redirect()->route('groupings.index')->with('success', 'Employees updated successfully');
    }
}
