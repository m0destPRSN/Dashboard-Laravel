<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return view('types.index', compact('types'));
    }

    public function create()
    {
        return view('types.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255|unique:types,type',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('types_photos', 'public');
        }

        Type::create([
            'type' => $validatedData['type'],
            'photo_path' => $photoPath,
        ]);
        return redirect()->route('types.index')->with('success', 'Тип успішно додано!');
    }

    public function edit(Type $type)
    {
        return view('types.edit', compact('type'));
    }

    public function update(Request $request, Type $type)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255|unique:types,type,' . $type->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoPath = $type->photo_path;
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('types_photos', 'public');
        }

        $type->update([
            'type' => $validatedData['type'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('types.index')->with('success', 'Тип успішно оновлено!');
    }

    public function destroy(Type $type)
    {
        // Delete photo if it exists
        if ($type->photo_path) {
            Storage::disk('public')->delete($type->photo_path);
        }
        $type->delete();
        return redirect()->route('types.index')->with('success', 'Тип успішно видалено!');
    }
}
