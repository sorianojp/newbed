<?php

namespace App\Http\Controllers;
use App\Models\NoDailyTimeRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class NoDailyTimeRecordController extends Controller
{
    public function index(): View
    {
        $noDTRs = NoDailyTimeRecord::all();
        return view('dtr.no-dtr.index', compact('noDTRs'));
    }
    public function create(): View
    {
        return view('dtr.no-dtr.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => ['required'],
            'effective_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ]);

        NoDailyTimeRecord::create($request->all());
        return redirect()->route('no-dtr.index')->with('success', 'No DTR Added!');
    }
}
