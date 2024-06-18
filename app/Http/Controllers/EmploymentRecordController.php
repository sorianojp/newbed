<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\EmploymentRecord;
use Illuminate\Http\Request;

class EmploymentRecordController extends Controller
{
    public function storeEmploymentRecord(Request $request, Employee $employee)
    {
        session(['activeSection' => 'employmentRecord']);
        $request->validate([
            'position_id' => 'required',
            'department_id' => 'required',
            'tenureship_id' => 'required',
            'base_salary' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        $employmentRecord = new EmploymentRecord($request->all());
        $employee->employmentRecords()->save($employmentRecord);
        return back()->with(['success' => 'Employment Record Added!']);
    }
}
