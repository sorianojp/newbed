<?php

namespace App\Http\Controllers;
use App\Models\Tenureship;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TenureshipController extends Controller
{
    public function index()
    {
        $tenureships = Tenureship::all();
        return view('tenureships.index', compact('tenureships'));
    }
    public function create(): View
    {
        return view('tenureships.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);
        Tenureship::create($request->all());
        return redirect()->route('tenureships.index')->with('success','Tenureship Added!');
    }
}
