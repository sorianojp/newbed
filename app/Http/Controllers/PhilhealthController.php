<?php

namespace App\Http\Controllers;

use App\Models\Philhealth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhilhealthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $philhealths = Philhealth::orderBy('bracket')->get();
        return view('payroll.contributions.philhealth.index', compact('philhealths'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payroll.contributions.philhealth.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'bracket' => ['required', 'numeric'],
            'start_range' => ['required', 'numeric'],
            'end_range' => ['required', 'numeric'],
            'base' => ['nullable', 'numeric'],
            'premium' => ['nullable', 'numeric'],
            'employee_share' => ['required', 'numeric'],
            'employer_share' => ['required', 'numeric'],
            'percentage' => ['nullable', 'numeric'],
        ]);

        Philhealth::create($validatedData);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Philhealth $philhealth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Philhealth $philhealth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Philhealth $philhealth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Philhealth $philhealth): RedirectResponse
    {
        $philhealth->delete();

        return redirect()->back();
    }
}
