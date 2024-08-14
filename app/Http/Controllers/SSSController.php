<?php

namespace App\Http\Controllers;

use App\Models\SSS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SSSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sss = SSS::all();
        return view('payroll.contributions.sss.index', compact('sss'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payroll.contributions.sss.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $validatedData = $request->validate([
            'name' => ['required'],
            'effective_date' => ['required', 'date'],
        ]);

        SSS::create($validatedData);

        return redirect()->route('sss.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SSS $sSS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SSS $sSS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SSS $sSS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SSS $sSS)
    {
        //
    }
}
