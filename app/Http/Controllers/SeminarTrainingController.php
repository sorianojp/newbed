<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\SeminarTraining;
use Illuminate\Http\Request;

class SeminarTrainingController extends Controller
{
    public function storeSeminarTraining(Request $request, Employee $employee)
    {
        session(['activeSection' => 'seminarTraining']);
        $request->validate([
            'title' => 'required',
            'venue' => 'nullable',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'hours' => 'nullable',
            'ld_type' => 'nullable',
            'conducted_sponsored' => 'nullable',
            'service_return' => 'nullable',
            'remark' => 'nullable',
        ]);
        $seminarTraining = new SeminarTraining($request->all());
        $employee->seminarTrainings()->save($seminarTraining);
        return back()->with(['success' => 'Seminar Training Added!']);
    }
}
