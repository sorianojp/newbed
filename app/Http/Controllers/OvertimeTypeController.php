<?php

namespace App\Http\Controllers;

use App\Models\OvertimeType;
use Illuminate\Http\Request;

class OvertimeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $overtimeTypes = OvertimeType::all();
        return view('dtr.overtime.overtime_types.index', compact('overtimeTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dtr.overtime.overtime_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required'],
            'rate' => ['required'],
        ]);

        OvertimeType::create($validatedData);

        return redirect()->route("overtime-types.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(OvertimeType $overtimeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OvertimeType $overtimeType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OvertimeType $overtimeType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OvertimeType $overtimeType)
    {
        //
    }
}
