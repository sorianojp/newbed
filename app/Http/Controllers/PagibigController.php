<?php

namespace App\Http\Controllers;

use App\Models\Pagibig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagibigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pagibigs = Pagibig::all();
        return view('payroll.contributions.pagibig.index', compact('pagibigs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payroll.contributions.pagibig.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $validatedData = $request->validate([
            'name' => ['required'],
            'effective_date' => ['required'],
            'percentage' => ['required'],
        ]);

        Pagibig::create($validatedData);

        return redirect()->route('pagibig.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pagibig $pagibig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagibig $pagibig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pagibig $pagibig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pagibig $pagibig)
    {
        //
    }
}
