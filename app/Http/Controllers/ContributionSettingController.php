<?php

namespace App\Http\Controllers;

use App\Models\ContributionSetting;
use Illuminate\Http\Request;

class ContributionSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contributions = ContributionSetting::all();
        return view('payroll.contributions.settings.index', compact('contributions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contribution' => ['required'],
            'period' => ['required'],
        ]);

        ContributionSetting::updateOrCreate(
            ['contribution' => $request->contribution],
            ['period' => $request->period]);

        return redirect()->route('contribution-settings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContributionSetting $contributionSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContributionSetting $contributionSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContributionSetting $contributionSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContributionSetting $contributionSetting)
    {
        //
    }
}
