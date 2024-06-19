<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Tenureship;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        session(['activeSection' => 'employmentRecord']);
        $validatedData = $request->validate([
            'employee_id_no' => 'required',
            'lastname' => 'required',
            'firstname' => 'required',
            'middlename' => 'nullable',
            'name_ext' => 'nullable',
            'mobile_no' => 'required',
            'personal_email' => 'nullable',
            'company_email' => 'nullable'

        ]);
        $employee = Employee::create($validatedData);
        if ($validatedData['company_email']) {
            $user = User::create([
                'name' => $validatedData['employee_id_no'],
                'email' => $validatedData['company_email'],
                'password' => Hash::make($validatedData['employee_id_no']),
            ]);
            $user->assignRole('employee');
            $employee->user_id = $user->id;
            $employee->save();
        }
        return redirect()->route('employees.show', ['employee' => $employee])
            ->with(['success' => 'Employee Added!'])->with(['note' => 'Please Add Employee\'s Employment Record!']);
    }
    public function show(Employee $employee)
    {
        $positions = Position::all();
        $departments = Department::all();
        $tenureships = Tenureship::all();
        return view('employees.show', compact('employee', 'positions', 'departments', 'tenureships'));
    }
}
