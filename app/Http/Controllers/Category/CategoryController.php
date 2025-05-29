<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load the 'type' relationship for efficiency
        $categories = Category::with('type')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::all(); // Pass types for the dropdown in the create form
        return view('categories.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category' => 'required|string|max:255|unique:categories,category',
            'id_type' => 'required|exists:types,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('categories_photos', 'public');

        }

        Category::create([
            'category' => $validatedData['category'],
            'id_type' => $validatedData['id_type'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('categories.index')->with('success', 'Категорію успішно додано!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $types = Type::all(); // Pass types for the dropdown in the edit form
        return view('categories.edit', compact('category', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'category' => 'required|string|max:255|unique:categories,category,' . $category->id,
            'id_type' => 'required|exists:types,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoPath = $category->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('categories_photos', 'public');
        }

        $category->update([
            'category' => $validatedData['category'],
            'id_type' => $validatedData['id_type'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('categories.index')->with('success', 'Категорію успішно оновлено!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->photo_path) {
            Storage::disk('public')->delete($category->photo_path);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Категорію успішно видалено!');
    }


}
