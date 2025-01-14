<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        return view('types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255|unique:types,type',
        ]);
        Type::create([
            'type' => $validatedData['type'],
        ]);
        return redirect()->route('types.index')->with('success', 'Тип успішно додано!');
    }

}

