<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\LocationRequest;
use App\Models\Category;
use App\Models\Location;
use App\Models\Type;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $locations=Location::all();
        $types=Type::all();
        $categories=Category::all();
        return view('locations.index',compact('locations','types','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $categories = Category::all();
        return view('locations.create_location', compact('types', 'categories'));
    }

    public function store(LocationRequest $request)
    {
        $validatedData=$request->validated();
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = $photo->hashName();
            $photoPath = $photo->storeAs('photos', $filename, 'public');
        }
        Location::create([
            'location' => $validatedData['location'],
            'id_type' => $validatedData['id_type'],
            'id_category' => $validatedData['id_category'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'photo_path' => $photoPath,
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);
        return response()->redirectToRoute('map')->with('success', 'Location created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
