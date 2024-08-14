<?php

namespace App\Http\Controllers;

use App\Models\SSS;
use App\Models\SSSBracket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SSSBracketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SSS $sss): View
    {
        return view('payroll.contributions.sss.sss-brackets.index', compact('sss'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SSS $sss)
    {
        return view('payroll.contributions.sss.sss-brackets.create', compact('sss'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SSS $sss)
    {
        $validatedData = $request->validate([
            'start_range' => ['required', 'numeric'],
            'end_range' => ['nullable', 'numeric'],
            'msc' => ['required', 'numeric'],
            'ec' => ['required', 'numeric'],
            'er' => ['required', 'numeric'],
            'ee' => ['required', 'numeric'],
        ]);

        $sss->sssBrackets()->create($validatedData);

        return redirect()->route('sss.sss-brackets.index', [
            'sss' => $sss,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SSSBracket $sssBracket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SSSBracket $sssBracket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SSSBracket $sssBracket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SSSBracket $sssBracket)
    {
        $sssBracket->delete();

        return redirect()->back();
    }
}
