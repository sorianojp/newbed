<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\GroupAffiliation;
use Illuminate\Http\Request;

class GroupAffiliationController extends Controller
{
    public function storeGroupAffiliation(Request $request, Employee $employee)
    {
        session(['activeSection' => 'groupAffiliation']);
        $request->validate([
            'name' => 'required',
            'position' => 'nullable',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
        ]);
        $groupAffiliation = new GroupAffiliation($request->all());
        $employee->groupAffiliations()->save($groupAffiliation);
        return back()->with(['success' => 'Group affiliation Added!']);
    }
}
