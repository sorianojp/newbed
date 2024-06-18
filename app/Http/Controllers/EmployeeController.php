<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Tenureship;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }
    public function create(): View
    {
        return view('employees.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id_no' => 'required',
            'lastname' => 'required',
            'firstname' => 'required',
            'middlename' => 'nullable',
            'name_ext' => 'nullable',
            'mobile_no' => 'required',
            'personal_email' => 'nullable',
            'company_email' => 'nullable'

        ]);
        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success','Employee Registered!');
    }
    public function show(Employee $employee)
    {
        $positions = Position::all();
        $departments = Department::all();
        $tenureships = Tenureship::all();
        return view('employees.show', compact('employee', 'positions', 'departments', 'tenureships'));
    }
}
