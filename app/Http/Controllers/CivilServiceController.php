<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\CivilService;
use Illuminate\Http\Request;

class CivilServiceController extends Controller
{
    public function storeCivilService(Request $request, Employee $employee)
    {
        session(['activeSection' => 'civilService']);
        $request->validate([
            'title' => 'required',
            'rating' => 'nullable',
            'exam_date' => 'required',
            'exam_place' => 'required',
            'license_no' => 'nullable',
            'validity_date' => 'nullable',
        ]);
        $civilService = new CivilService($request->all());
        $employee->civilServices()->save($civilService);
        return back()->with(['success' => 'Civil Service Added!']);
    }
}
