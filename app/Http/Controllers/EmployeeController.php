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
        $positions = Position::all();
        $departments = Department::all();
        $tenureships = Tenureship::all();
        return view('employees.create', compact('positions', 'departments', 'tenureships'));
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required',
            'lastname' => 'required',
            'firstname' => 'required',
            'middlename' => 'nullable',
            'name_ext' => 'nullable',
            'mobile_no' => 'required',
            'personal_email' => 'nullable',
            'company_email' => 'nullable',
            'position_id' => 'required',
            'department_id' => 'required',
            'tenureship_id' => 'required',
            'base_salary' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success','Employee Registered!');
    }
    public function createPersonalData(Employee $employee): View
    {
        return view('employees.personalData', compact('employee'));
    }
}
