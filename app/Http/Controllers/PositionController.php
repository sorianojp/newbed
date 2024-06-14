<?php

namespace App\Http\Controllers;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PositionController extends Controller
{
    public function index(): View
    {
        $positions = Position::all();
        return view('positions.index', compact('positions'));
    }
    public function create(): View
    {
        return view('positions.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);
        Position::create($request->all());
        return redirect()->route('positions.index')->with('success','Position Added!');
    }
}
