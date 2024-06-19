<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\EducationalAttainment;
use Illuminate\Http\Request;

class EducationalAttainmentController extends Controller
{
    public function storeEducationalAttainment(Request $request, Employee $employee)
    {
        session(['activeSection' => 'educationalAttainment']);
        $request->validate([
            'level' => 'required',
            'course' => 'required',
            'school' => 'required',
            'address' => 'required',
            'year_started' => 'required',
            'year_ended' => 'nullable',
            'year_graduated' => 'nullable',
            'units' => 'nullable',
            'honor' => 'nullable',
        ]);
        $educationalAttainment = new EducationalAttainment($request->all());
        $employee->educationalAttainments()->save($educationalAttainment);
        return back()->with(['success' => 'Educational Attainment Added!']);
    }
}
