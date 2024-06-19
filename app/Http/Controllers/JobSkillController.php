<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\JobSkill;
use Illuminate\Http\Request;

class JobSkillController extends Controller
{
    public function storeJobSkill(Request $request, Employee $employee)
    {
        session(['activeSection' => 'jobSkill']);
        $request->validate([
            'skill' => 'required',
            'level' => 'nullable',
        ]);
        $jobSkill = new JobSkill($request->all());
        $employee->jobSkills()->save($jobSkill);
        return back()->with(['success' => 'Job Skill Added!']);
    }
}
