<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\LocationRequest;
use App\Models\Category;
use App\Models\Location;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Eager load the user relationship
        $locations = Location::with('user')->get();
        $types = Type::all();
        $categories = Category::all();
        // This view is used by the 'admin.locations.index' route
        return view('locations.index', compact('locations', 'types', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $types = Type::all();
        $categories = Category::all();
        return view('locations.create_location', compact('types', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Location\LocationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LocationRequest $request)
    {
        $validatedData = $request->validated();
        $photoPaths = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = $photo->hashName();
                $path = $photo->storeAs('photos', $filename, 'public');
                $photoPaths[] = $path;
            }
        }

        Location::create([
            'user_id' => Auth::id(), // Add the authenticated user's ID
            'location' => $validatedData['location'],
            'id_type' => $validatedData['id_type'],
            'id_category' => $validatedData['id_category'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'photo_paths' => $photoPaths, // Will be an empty array if no photos uploaded
            'start_time' => $validatedData['start_time'] ?? null, // Handle if nullable
            'end_time' => $validatedData['end_time'] ?? null,     // Handle if nullable
        ]);

        return response()->redirectToRoute('map')->with('success', 'Location created successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        // Eager load the user relationship
        $location = Location::with('user')->findOrFail($id);
        // Assuming Location model relationships for type and category might not be set up to use id_type/id_category yet,
        // or the view 'locations.single' expects $type and $category separately.
        $type = Type::find($location->id_type);
        $category = Category::find($location->id_category);

        return view('locations.single', compact('location', 'type', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $location = \App\Models\Location::findOrFail($id);
        $types = \App\Models\Type::all();
        $categories = \App\Models\Category::all();
        $users = \App\Models\User::all(); // Fetch all users
        return view('locations.edit', compact('location', 'types', 'categories', 'users')); // Add 'users'
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Location\LocationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(\App\Http\Requests\Location\LocationRequest $request, $id)
    {
        $location = \App\Models\Location::findOrFail($id);
        $validatedData = $request->validated();

        $updateData = [
            'user_id' => $validatedData['user_id'], // Add this line
            'location' => $validatedData['location'],
            'id_type' => $validatedData['id_type'],
            'id_category' => $validatedData['id_category'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'start_time' => $validatedData['start_time'] ?? null,
            'end_time' => $validatedData['end_time'] ?? null,
        ];

        if ($request->hasFile('photos')) {
            // ... (photo handling logic remains the same) ...
            if ($location->photo_paths && is_array($location->photo_paths)) {
                foreach ($location->photo_paths as $oldPhotoPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPhotoPath);
                }
            }
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $filename = $photo->hashName();
                $path = $photo->storeAs('photos', $filename, 'public');
                $photoPaths[] = $path;
            }
            $updateData['photo_paths'] = $photoPaths;
        }

        $location->update($updateData);

        return redirect()->route('admin.locations.index')->with('success', 'Локація успішно оновлена!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $location = Location::findOrFail($id);

        // Delete associated photos from storage
        if ($location->photo_paths && is_array($location->photo_paths)) {
            foreach ($location->photo_paths as $photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
        }

        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully!');
    }

    public function myLocations()
    {
        $user = auth()->user();
        $locations = \App\Models\Location::where('user_id', $user->id)->get();
        return view('locations.my_locations', compact('locations'));
    }

}
