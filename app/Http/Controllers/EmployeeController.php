<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Tenureship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            'company_email' => 'nullable',

        ]);
        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee Registered!');
    }
    public function show(Employee $employee)
    {
        $positions = Position::all();
        $departments = Department::all();
        $tenureships = Tenureship::all();
        return view('employees.show', compact('employee', 'positions', 'departments', 'tenureships'));
    }

    public function searchEmployees(Request $request): JsonResponse
    {
        $query = strtolower($request->input('search'));

        // Return an empty array if the search query is empty
        if (empty($query)) {
            return response()->json([]);
        }

        // Check if the query contains a comma
        if (strpos($query, ',') !== false) {
            // Split the query into lastname and firstname parts
            list($lastname_query, $firstname_query) = array_map('trim', explode(',', $query));

            // Split the firstname part into individual names if there are multiple names
            $firstnames = explode(' ', $firstname_query);

            $employees = Employee::where(function ($q) use ($lastname_query, $firstnames) {
                // Match lastname and firstnames in sequence
                $q->whereRaw('LOWER(lastname) LIKE ?', ['%' . $lastname_query . '%'])
                    ->where(function ($q2) use ($firstnames) {
                        foreach ($firstnames as $name) {
                            $q2->whereRaw('LOWER(firstname) LIKE ?', ['%' . $name . '%'])
                                ->orWhereRaw('LOWER(middlename) LIKE ?', ['%' . $name . '%']);
                        }
                    });
            })->orWhere(function ($q) use ($lastname_query, $firstnames) {
                // Match concatenated full name
                $q->whereRaw('CONCAT(LOWER(lastname), ", ", LOWER(firstname)) LIKE ?', ['%' . $lastname_query . ', ' . implode(' ', $firstnames) . '%'])
                    ->orWhereRaw('CONCAT(LOWER(lastname), ", ", LOWER(firstname), " ", LOWER(middlename)) LIKE ?', ['%' . $lastname_query . ', ' . implode(' ', $firstnames) . '%']);
            })->orWhereRaw('LOWER(employee_id_no) LIKE ?', ['%' . $query . '%'])
                ->take(10)
                ->get();
        } else {
            // Handle normal search without comma
            $names = explode(' ', $query);

            $employees = Employee::where(function ($q) use ($names) {
                if (count($names) > 1) {
                    // Handle case where there are multiple names
                    $q->where(function ($q2) use ($names) {
                        foreach ($names as $index => $name) {
                            if ($index === 0) {
                                $q2->whereRaw('LOWER(firstname) LIKE ?', ['%' . $name . '%']);
                            } else {
                                $q2->whereRaw('LOWER(lastname) LIKE ?', ['%' . $name . '%'])
                                    ->orWhereRaw('LOWER(middlename) LIKE ?', ['%' . $name . '%']);
                            }
                        }
                    })->orWhere(function ($q2) use ($names) {
                        $q2->whereRaw('CONCAT(LOWER(firstname), " ", LOWER(middlename), " ", LOWER(lastname)) LIKE ?', ['%' . implode(' ', $names) . '%']);
                    });
                } else {
                    // Handle single name search
                    $q->whereRaw('LOWER(firstname) LIKE ?', ['%' . $names[0] . '%'])
                        ->orWhereRaw('LOWER(lastname) LIKE ?', ['%' . $names[0] . '%'])
                        ->orWhereRaw('LOWER(middlename) LIKE ?', ['%' . $names[0] . '%']);
                }
            })->orWhereRaw('LOWER(employee_id_no) LIKE ?', ['%' . $query . '%'])
                ->take(10)
                ->get();
        }

        return response()->json($employees);
    }
}
