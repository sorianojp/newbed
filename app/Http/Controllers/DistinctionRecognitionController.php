<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\DistinctionRecognition;
use Illuminate\Http\Request;

class DistinctionRecognitionController extends Controller
{
    public function storeDistinctionRecognition(Request $request, Employee $employee)
    {
        session(['activeSection' => 'distinctionRecognition']);
        $request->validate([
            'title' => 'required',
            'place' => 'nullable',
            'date' => 'nullable',
            'agency_org' => 'nullable',
            'remark' => 'nullable'
        ]);
        $distinctionRecognition = new DistinctionRecognition($request->all());
        $employee->distinctionRecognitions()->save($distinctionRecognition);
        return back()->with(['success' => 'Distinction Recognition Added!']);
    }
}
