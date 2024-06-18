<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeePersonalDataController extends Controller
{
    public function storePersonalData(Request $request, Employee $employee)
    {
        session(['activeSection' => 'personalData']);
        $employee->personalData()->updateOrCreate([],$request->all());
        return redirect()->route('employees.show', $employee)->with('success','Personal Data Updated!');
    }
}
