<?php

namespace App\Http\Controllers\LinkOnMap;

use App\Http\Controllers\Controller;
use App\Models\LinkOnMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class LinkOnMapController extends Controller
{
    public function index()
    {
        $linkonmaps = LinkOnMap::latest()->paginate(10);
        return view('linkonmap.index', compact('linkonmaps'));
    }

    public function create()
    {
        return view('linkonmap.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'link' => [
                'required',
                'string',
                'max:255',
                // Accepts /map/search?type=NUMBER or /map/search?category=NUMBER
                'regex:/^\/map\/search\?(type|category)=\d+$/'
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'link.regex' => 'The link must be like /map/search?type=23 or /map/search?category=5',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('linkonmap_photos', 'public');
        }

        LinkOnMap::create([
            'name' => $validatedData['name'],
            'link' => $validatedData['link'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('links-on-map.index')->with('success', 'Link created successfully.');
    }

    public function edit(LinkOnMap $link)
    {
        return view('linkonmap.edit', ['linkonmap' => $link]);
    }

    public function update(Request $request, LinkOnMap $link)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'link' => [
                'required',
                'string',
                'max:255',
                'regex:/^\/map\/search\?(type|category)=\d+$/'
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'link.regex' => 'The link must be like /map/search?type=23 or /map/search?category=5',
        ]);

        $photoPath = $link->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('linkonmap_photos', 'public');
        }

        $link->update([
            'name' => $validatedData['name'],
            'link' => $validatedData['link'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('links-on-map.index')->with('success', 'Link updated successfully.');
    }

    public function destroy(LinkOnMap $link)
    {
        if ($link->photo_path) {
            Storage::disk('public')->delete($link->photo_path);
        }
        $link->delete();
        return redirect()->route('links-on-map.index')->with('success', 'Link deleted successfully.');
    }

    public function getAllLinksJson(): JsonResponse
    {
        $links = LinkOnMap::all();
        return response()->json($links);
    }

}
