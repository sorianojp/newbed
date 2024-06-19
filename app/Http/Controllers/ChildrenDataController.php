<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\ChildrenData;
use Illuminate\Http\Request;

class ChildrenDataController extends Controller
{
    public function storeChildrenData(Request $request, Employee $employee)
    {
        session(['activeSection' => 'childrenData']);
        $request->validate([
            'full_name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
        ]);
        $childrenData = new ChildrenData($request->all());
        $employee->childrenDatas()->save($childrenData);
        return back()->with(['success' => 'Children Data Added!']);
    }
}
