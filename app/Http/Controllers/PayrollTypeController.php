<?php

namespace App\Http\Controllers;
use App\Models\PayrollType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PayrollTypeController extends Controller
{
    public function index(): View
    {
        $payrollTypes = PayrollType::all();
        return view('payroll-types.index', compact('payrollTypes'));
    }
    public function create(): View
    {
        return view('payroll-types.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        PayrollType::create($request->all());
        return redirect()->route('payroll-types.index')->with('success', 'Payroll Type Added!');
    }
}
