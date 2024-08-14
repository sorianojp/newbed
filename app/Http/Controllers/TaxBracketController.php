<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\TaxBracket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxBracketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tax $tax): View
    {
        return view('payroll.contributions.tax.tax-brackets.index', compact('tax'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Tax $tax): View
    {
        return view('payroll.contributions.tax.tax-brackets.create', compact('tax'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tax $tax)
    {
        $validatedData = $request->validate([
            'period' => ['required'],
            'bracket' => ['required'],
            'start_range' => ['nullable', 'numeric'],
            'end_range' => ['nullable', 'numeric'],
            'fixed_amount' => ['required'],
            'excess_percentage' => ['required'],
        ]);

        $tax->taxBrackets()->create($validatedData);

        return redirect()->route('taxes.tax-brackets.index', $tax);

    }

    /**
     * Display the specified resource.
     */
    public function show(TaxBracket $taxBracket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxBracket $taxBracket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxBracket $taxBracket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxBracket $taxBracket)
    {
        $taxBracket->delete();

        return back()->with('status', 'Successfully deleted bracket');
    }
}
