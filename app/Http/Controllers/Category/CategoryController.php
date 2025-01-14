<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $types = Type::all();
        return view('categories.index', compact('categories', 'types'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category' => 'required|string|max:255|unique:categories,category',
            'id_type' => 'required|exists:types,id',
        ]);

        Category::create([
            'category' => $validatedData['category'],
            'id_type' => $validatedData['id_type']
        ]);

        return redirect()->route('categories.index')->with('success', 'Категорію успішно додано!');
    }

}
